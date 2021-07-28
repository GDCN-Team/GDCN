<?php

namespace Modules\NGProxy\Http\Controllers;

use GDCN\GDObject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\NGProxy\Entities\Song;
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
     * @throws ProxyFailedException|SongGetException
     */
    public function getSong(int $songID): mixed
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
                    'secret' => 'Wmfd2893gb7'
                ]);

            $response = $req->body();
            $levelObjectParts = explode('#', $response);
            $songString = $levelObjectParts[1] ?? null;
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

            $oss->put($object, $song_content);
        }

        $song->download_link = $download_link;
    }

    /**
     * @param int $songID
     * @return string
     * @throws ProxyFailedException
     * @throws SongGetException
     */
    public function getInfo(int $songID): string
    {
        $song = $this->getSong($songID);
        return $song->toJson();
    }

    /**
     * @param int $songID
     * @return string
     * @throws ProxyFailedException
     * @throws SongGetException
     */
    public function getObject(int $songID): string
    {
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
