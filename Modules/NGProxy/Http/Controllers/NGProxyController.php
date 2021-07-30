<?php

namespace Modules\NGProxy\Http\Controllers;

use App\Models\Game\CustomSong;
use GDCN\GDObject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\NGProxy\Entities\Song;
use Modules\NGProxy\Exceptions\SongDisabledException;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\Proxy\Exceptions\ProxyFailedException;
use Modules\Proxy\Http\Controllers\ProxyController;

/**
 * Class NGProxyController
 * @package Modules\NGProxy\Http\Controllers
 */
class NGProxyController extends Controller
{
    /**
     * @var string
     */
    public string $cdn_domain = 'https://cdn.geometrydashchinese.com';

    /**
     * @var string
     */
    public string $oss_prefix = 'ngproxy/songs';

    /**
     * NGProxyController constructor.
     * @param ProxyController $proxy
     */
    public function __construct(
        public ProxyController $proxy
    )
    {
    }

    /**
     * @param int $songID
     * @return mixed
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     */
    protected function getSong(int $songID): mixed
    {
        if ($song = Song::find($songID)) {
            return $song;
        }

        $GDProxy = app(GDProxyController::class);
        $url = $GDProxy->gdServer . '/getGJSongInfo.php';

        $req = $this->proxy->getInstance()
            ->asForm()
            ->post($url, [
                'songID' => $songID,
                'secret' => 'Wmfd2893gb7'
            ]);

        $songString = $req->body();
        if ($songString < 0 || empty($songString) || !$req->ok()) {
            $url = $GDProxy->gdServer . '/getGJLevels21.php';
            $req = $this->proxy->getInstance()
                ->asForm()
                ->post($url, [
                    'song' => $songID,
                    'customSong' => true,
                    'secret' => 'Wmfd2893gb7'
                ]);

            $response = $req->body();
            $levelObjectParts = explode('#', $response);
            $songString = $levelObjectParts[2] ?? null;
            if (empty($songString)) {
                throw new ProxyFailedException();
            }
        }

        $songObject = GDObject::split($songString, '~|~');
        if (empty($songObject[10])) {
            throw new SongGetException();
        }

        $song = new Song();
        $song->id = $songObject[1];
        $song->name = $songObject[2];
        $song->author_id = $songObject[3] ?? null;
        $song->author_name = $songObject[4];
        $song->size = $songObject[5];
        $song->video_id = $songObject[6] ?? null;
        $song->author_youtube_url = $songObject[7] ?? null;
        $song->download_link = $songObject[10];
        $this->processSong($song);
        $song->save();

        if ($song->disabled) {
            throw new SongDisabledException();
        }

        return $song;
    }

    /**
     * @param Song $song
     */
    protected function processSong(Song $song): void
    {
        $oss = Storage::disk('oss');
        $link = urldecode($song->download_link);
        $object = $this->oss_prefix . '/' . $song->id . '_' . sha1($song->id . 'NGProxy') . '.mp3';
        $download_link = urlencode($this->cdn_domain . '/' . $object);
        if (!$oss->exists($object)) {
            $song_content = $this->proxy->getInstance()
                ->get($link)
                ->body();

            if ($song_content < $song->size * 1024 * 1024) {
                $song->disabled = true;
                return;
            }

            $oss->put($object, $song_content);
        }

        $song->download_link = $download_link;
    }

    /**
     * @param int $songID
     * @return string
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getInfo(int $songID): string
    {
        return $this->getSong($songID)->toJson();
    }

    /**
     * @param int $songID
     * @return CustomSong
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getCustomSong(int $songID): CustomSong
    {
        $song = CustomSong::firstWhere('song_id', $songID);

        if (!$song) {
            throw new SongGetException();
        }

        if ($song->disabled) {
            throw new SongDisabledException();
        }

        return $song;
    }

    /**
     * @param int $songID
     * @return string
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getCustomSongObject(int $songID): string
    {
        $song = $this->getCustomSong($songID);

        return GDObject::merge([
            1 => $song->song_id,
            2 => $song->name,
            3 => 7,
            4 => $song->author_name,
            5 => $song->size,
            6 => null,
            7 => null,
            10 => urlencode($song->download_url)
        ], '~|~');
    }

    /**
     * @param int $songID
     * @param bool $getCustomSong
     * @return string
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getObject(int $songID, bool $getCustomSong = false): string
    {
        if ($songID >= config('game.customSongIdOffset') && $getCustomSong) {
            return $this->getCustomSongObject($songID);
        }

        $song = $this->getSong($songID);
        return GDObject::merge([
            1 => $song->id,
            2 => $song->name,
            3 => $song->author_id,
            4 => $song->author_name,
            5 => $song->size,
            6 => $song->video_id,
            7 => $song->author_youtube_url,
            10 => $song->download_link
        ], '~|~');
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return string
     */
    public function getTopArtists(int $page, int $perPage = 20): string
    {
        return Song::forPage($page, $perPage)
            ->get()
            ->map(function(Song $song) {
                return GDObject::merge([
                    4 => $song->author_name,
                    7 => $song->author_youtube_url
                ], ':');
            })->join('|');
    }
}
