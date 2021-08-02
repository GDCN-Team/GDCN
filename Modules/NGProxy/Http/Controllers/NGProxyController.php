<?php

namespace Modules\NGProxy\Http\Controllers;

use App\Http\Controllers\Web\Traits\ResponseTrait;
use GDCN\GDObject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\NGProxy\Entities\Application;
use Modules\NGProxy\Entities\ApplicationUser;
use Modules\NGProxy\Entities\ApplicationUserTraffic;
use Modules\NGProxy\Entities\CustomSong;
use Modules\NGProxy\Entities\Song;
use Modules\NGProxy\Entities\TrafficCode;
use Modules\NGProxy\Exceptions\SongDisabledException;
use Modules\NGProxy\Exceptions\SongDownloadException;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\NGProxy\Exceptions\SongSaveException;
use Modules\NGProxy\Exceptions\TrafficGetException;
use Modules\Proxy\Http\Controllers\ProxyController;

/**
 * Class NGProxyController
 * @package Modules\NGProxy\Http\Controllers
 */
class NGProxyController extends Controller
{
    use ResponseTrait;

    /**
     * @var string
     */
    public string $cdn_domain = 'https://cdn.geometrydashchinese.com';

    /**
     * @var string
     */
    public string $oss_prefix = 'ngproxy/songs';

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
     * @return Song|CustomSong
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongSaveException
     */
    protected function getSongModel(int $songID): Song|CustomSong
    {
        // 自定义歌曲
        if ($songID >= config('ngproxy.custom_song_offset')) {
            return $this->getCustomSongModel($songID);
        }

        if (!$song = Song::find($songID)) {
            $songObject = $this->getSongObjectFromOfficialServer($songID);

            try {
                $song = new Song();
                $song->id = $songObject[1];
                $song->name = $songObject[2];
                $song->author_id = $songObject[3] ?? null;
                $song->author_name = $songObject[4];
                $song->size = $songObject[5];
                $song->video_id = $songObject[6] ?? null;
                $song->author_youtube_url = $songObject[7] ?? null;
                $song->download_link = $this->getOssDownloadLink($song->id, $songObject[10]);
                $song->save();
            } catch (SongDownloadException) {
                $song->download_link = $songObject[10];
                $song->disabled = true;
                $song->save();
            }
        }

        if (!$song instanceof Song) {
            throw new SongGetException();
        }

        if ($song->disabled) {
            throw new SongDisabledException();
        }

        $song->download_link = $this->getDownloadLinkUsingUserTraffic($song);
        return $song;
    }

    /**
     * @param int $songID
     * @return CustomSong
     * @throws SongGetException
     */
    protected function getCustomSongModel(int $songID): CustomSong
    {
        $song = CustomSong::whereSongId($songID)->first();
        if (!$song instanceof CustomSong) {
            throw new SongGetException();
        }

        $song->download_link = $this->getDownloadLinkUsingUserTraffic($song);
        return $song;
    }

    /**
     * @param int $songID
     * @return array
     * @throws SongGetException
     */
    protected function getSongObjectFromOfficialServer(int $songID): array
    {
        $GDProxy = app(GDProxyController::class);
        $req = $this->proxy
            ->getInstance()
            ->asForm()
            ->post($GDProxy->gdServer . '/getGJSongInfo.php', [
                'songID' => $songID,
                'secret' => 'Wmfd2893gb7'
            ]);

        $songString = $req->body();
        if ($songString < 0 || empty($songString) || !$req->ok()) {
            $req = $this->proxy
                ->getInstance()
                ->asForm()
                ->post($GDProxy->gdServer . '/getGJLevels21.php', [
                    'song' => $songID,
                    'customSong' => true,
                    'secret' => 'Wmfd2893gb7'
                ]);

            $response = $req->body();
            $songString = explode('#', $response)[2] ?? null;
        }

        if (empty($songString)) {
            throw new SongGetException();
        }

        return GDObject::split($songString, '~|~');
    }

    /**
     * @param int $songID
     * @return string
     */
    protected function getOssObject(int $songID): string
    {
        return $this->oss_prefix . '/' . $songID . '_' . sha1($songID . 'NGProxy') . '.mp3';
    }

    /**
     * @param int $songID
     * @param string $download_link
     * @return string
     * @throws SongDownloadException
     * @throws SongSaveException
     */
    protected function getOssDownloadLink(int $songID, string $download_link): string
    {
        // 变量
        $link = urldecode($download_link);
        $object = $this->getOssObject($songID);

        // 检测歌曲是否存在oss中
        $oss = Storage::disk('oss');
        $cdnUrl = urlencode($this->cdn_domain . '/' . $object);
        if (!$oss->exists($object)) {
            $req = $this->proxy
                ->getInstance()
                ->get($link);

            if (!$req->ok()) {
                throw new SongDownloadException();
            }

            $response = $req->body();
            if ($oss->put($object, $response)) {
                return $cdnUrl;
            }

            throw new SongSaveException();
        }

        return $cdnUrl;
    }

    /**
     * @param Song|CustomSong $song
     * @return string
     */
    protected function getDownloadLinkUsingUserTraffic(Song|CustomSong $song): string
    {
        $defaultURL = URL::signedRoute('song.download', [
            '_' => Crypt::encryptString("$song->id|0")
        ]);

        // 获取应用
        if ($application = Application::whereAppId($this->app_id)->first()) {
            // 获取用户
            if ($user = ApplicationUser::where(['bind_ip' => $this->request->ip(), 'app_id' => $application->id])->first()) {
                if (!$traffic = $user->traffic) {
                    $traffic = new ApplicationUserTraffic();
                    $traffic->user_id = $user->id;
                    $traffic->traffic_count = 0;
                    $traffic->save();
                }

                return URL::signedRoute('song.download', [
                    '_' => Crypt::encryptString("$song->id|$traffic->id")
                ]);
            }
        }

        return $defaultURL;
    }

    /**
     * @param int $trafficID
     * @return ApplicationUserTraffic
     * @throws TrafficGetException
     */
    protected function getTrafficModel(int $trafficID): ApplicationUserTraffic
    {
        $traffic = ApplicationUserTraffic::find($trafficID);
        if (!$traffic instanceof ApplicationUserTraffic) {
            throw new TrafficGetException();
        }

        return $traffic;
    }

    /**
     * @param int $songID
     * @return string
     */
    protected function getSpeedLink(int $songID): string
    {
        $oss = Storage::disk('oss');
        $object = $this->getOssObject($songID);
        $now = Carbon::now();
        $tenMinutesLater = $now->addMinutes(10);
        return $oss->temporaryUrl($object, $tenMinutesLater);
    }

    /**
     * @param $song
     * @return Song|CustomSong
     */
    protected function processSongInfo($song): Song|CustomSong
    {
        if ($song instanceof CustomSong) {
            $song->id = $song->song_id;
            unset($song->song_id);
        }

        return $song;
    }

    /**
     * @param int $songID
     * @return array
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongSaveException
     */
    public function getInfo(int $songID): array
    {
        $song = $this->getSongModel($songID);
        return $this->response(
            true,
            null,
            $this->processSongInfo($song)
        );
    }

    /**
     * @param int $songID
     * @return string
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongSaveException
     */
    public function getObject(int $songID): string
    {
        $song = $this->processSongInfo(
            $this->getSongModel($songID)
        );

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
            ->orderByDesc('level_count')
            ->get()
            ->map(function (Song $song) {
                return GDObject::merge([
                    4 => $song->author_name,
                    7 => $song->author_youtube_url
                ], ':');
            })->join('|');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongSaveException
     * @throws TrafficGetException
     */
    public function download(Request $request): RedirectResponse
    {
        if (!$_ = $request->get('_')) {
            abort(404);
        }

        $_ = Crypt::decryptString($_);
        [$songID, $trafficID] = explode('|', $_);

        $song = $this->getSongModel($songID);
        $song->refresh();

        if ($trafficID) {
            $traffic = $this->getTrafficModel($trafficID);

            $remaining = $traffic->traffic_count - $song->size;
            $url = $remaining > 0 ? $this->getSpeedLink($song->id) : $song->download_link;
        } else {
            $url = $song->download_link;
        }

        return Redirect::away(
            urldecode($url)
        );
    }

    /**
     * @param int $userID
     * @param string $code
     * @return array
     */
    public function activeCode(int $userID, string $code): array
    {
        if ($user = ApplicationUserTraffic::whereUserId($userID)->first()) {
            if ($code = TrafficCode::whereActiveCode($code)->first()) {
                if ($code->used) {
                    return $this->response(false, '该兑换码已被兑换过了');
                }

                $user->traffic_count += $code->traffic_count;
                $user->save();

                $code->used = true;
                $code->save();

                return $this->response(true, '兑换成功!');
            } else {
                return $this->response(false, '兑换码不存在(或未找到)');
            }
        } else {
            return $this->response(false, '用户不存在(或未找到)');
        }
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
