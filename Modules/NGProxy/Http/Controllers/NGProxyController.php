<?php

namespace Modules\NGProxy\Http\Controllers;

use GDCN\GDObject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\NGProxy\Entities\Application;
use Modules\NGProxy\Entities\ApplicationUser;
use Modules\NGProxy\Entities\ApplicationUserTraffic;
use Modules\NGProxy\Entities\CustomSong;
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
     * @var bool
     */
    public bool $is_custom_song = false;

    /**
     * @var string|null
     */
    public ?string $app_id;

    /**
     * NGProxyController constructor.
     * @param ProxyController $proxy
     * @param Request $request
     */
    public function __construct(
        public ProxyController $proxy,
        public Request         $request
    )
    {
        $this->app_id = $this->request->get('app_id');
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
        if ($songID >= config('ngproxy.custom_song_offset')) {
            return $this->getCustomSong($songID);
        }

        if ($song = Song::find($songID)) {
            if ($song->disabled) {
                throw new SongDisabledException();
            }

            $this->processSongDownloadLink($song);
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

        $this->processSongDownloadLink($song);
        return $song;
    }

    /**
     * @param Song $song
     * @return string
     */
    protected function getFileName(Song $song): string
    {
        return $this->oss_prefix . '/' . $song->id . '_' . sha1($song->id . 'NGProxy') . '.mp3';
    }

    /**
     * @param Song $song
     */
    protected function processSong(Song $song): void
    {
        $oss = Storage::disk('oss');
        $link = urldecode($song->download_link);
        $object = $this->getFileName($song);
        $download_link = urlencode($this->cdn_domain . '/' . $object);
        if (!$oss->exists($object)) {
            $req = $this->proxy->getInstance()
                ->get($link);

            $response = $req->body();
            if (Str::contains($response, '404')) {
                $song->disabled = true;
                return;
            }

            $oss->put($object, $response);
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
        $song = $this->getSong($songID);

        if ($this->is_custom_song) {
            $song->id = $song->song_id;
            unset($song->song_id);
        }

        return $song->toJson();
    }

    /**
     * @param int $songID
     * @return string
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getObject(int $songID): string
    {
        $song = $this->getSong($songID);
        return GDObject::merge([
            1 => $this->is_custom_song ? $song->song_id : $song->id,
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
            ->map(function (Song $song) {
                return GDObject::merge([
                    4 => $song->author_name,
                    7 => $song->author_youtube_url
                ], ':');
            })->join('|');
    }

    /**
     * @param int $songID
     * @return object|null
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getCustomSong(int $songID): null|object
    {
        if ($song = CustomSong::whereSongId($songID)->first()) {
            if ($song->disabled) {
                throw new SongDisabledException();
            }

            $this->is_custom_song = true;
            $this->processSongDownloadLink($song);
            return $song;
        }

        throw new SongGetException();
    }

    public function processSongDownloadLink($song)
    {
        $appID = $this->app_id;
        $app = Application::whereAppId($appID)->first();
        if (!$app) {
            return;
        }

        $user = ApplicationUser::where([
            'app_id' => $app->id,
            'bind_ip' => $this->request->ip()
        ])->first();
        if (!$user) {
            return;
        }

        $traffic = ApplicationUserTraffic::whereUserId($user->id)->first();
        if (!$traffic) {
            $traffic = new ApplicationUserTraffic();
            $traffic->user_id = $user->id;
            $traffic->traffic_count = 0;
            $traffic->save();
        }

        $traffic_count = ($traffic->traffic_count - $song->size);
        if ($traffic_count > 0) {
            /** @var Song|CustomSong $song */
            $file = $this->getFileName($song);
            $url = Storage::disk('oss')->temporaryUrl($file, now()->addMinutes(10));
            $song->download_link = URL::signedRoute('song.download', [
                '_' => Crypt::encryptString($url . '|' . $traffic->id . '|' . $song->size)
            ]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function download(Request $request): RedirectResponse
    {
        if (!$_ = $request->get('_')) {
            abort(404);
        }

        $_ = Crypt::decryptString($_);
        [$url, $trafficID, $size] = explode('|', $_);

        $traffic = ApplicationUserTraffic::findOrFail($trafficID);
        $traffic->traffic_count -= $size;
        $traffic->save();

        return Redirect::away($url);
    }

    /**
     * @param string $app_id
     * @return NGProxyController
     */
    public function setAppId(string $app_id): NGProxyController
    {
        $this->app_id = $app_id;
        return $this;
    }
}
