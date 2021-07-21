<?php

namespace Modules\Newgrounds\Http\Controllers;

use App\Exceptions\Newgrounds\SongDisabledException;
use App\Exceptions\Newgrounds\SongGetException;
use GDCN\GDObject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Newgrounds\Entities\Song;
use Modules\Proxy\Http\Controllers\ProxyController;

class NewgroundsController extends Controller
{
    public string $prefix = 'ngproxy/songs';
    public string $cdn = 'http://cdn.geometrydashchinese.com';

    public function __construct(
        protected ProxyController $proxyController
    )
    {
    }

    /**
     * @param int $songID
     * @return array
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function info(int $songID): array
    {
        return $this->get($songID)->toArray();
    }

    /**
     * @param int $songID
     * @return mixed
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function get(int $songID): mixed
    {
        if ($song = Song::find($songID)) {
            if ($song->disabled) {
                throw new SongDisabledException();
            }

            return $song;
        }

        // 从官服歌曲接口拿数据
        $response = $this->proxyController->post(
            $this->proxyController->baseURL . '/getGJSongInfo.php',
            [
                'songID' => $songID,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        if ($response <= 0 || empty($response)) {
            // 从官服关卡接口拿数据
            $response = $this->proxyController->post(
                $this->proxyController->baseURL . '/getGJLevels21.php',
                [
                    'song' => $songID,
                    'secret' => 'Wmfd2893gb7'
                ]
            );

            $response = explode('#', $response)[1];
        }

        $songObject = GDObject::split($response, '~|~');
        if (empty($songObject[10])) {
            throw new SongGetException();
        }

        //检查oss
        $oss = Storage::disk('oss');
        $object = $this->prefix . '/' . $songID . '_' . sha1($songID . 'NGProxy') . '.mp3';
        if (!$oss->exists($object)) {
            $oss->put($object, $this->proxyController->get(urldecode($songObject[10])));
        }

        $song = new Song();
        $song->id = $songObject[1];
        $song->name = $songObject[2];
        $song->author_id = $songObject[3];
        $song->author_name = $songObject[4];
        $song->size = $songObject[5];
        $song->youtube_video_id = $songObject[6];
        $song->author_youtube_url = $songObject[7] ?? null;
        $song->download_url = $this->cdn . '/' . $object;
        $song->save();

        return $song;
    }

    /**
     * @param int $songID
     * @return string
     * @throws SongDisabledException
     * @throws SongGetException
     */
    public function getObject(int $songID): string
    {
        $song = $this->get($songID);

        return GDObject::merge([
            1 => $song->id,
            2 => $song->name,
            3 => $song->author_id,
            4 => $song->author_name,
            5 => $song->size,
            6 => $song->youtube_video_id,
            7 => $song->author_youtube_url,
            10 => $song->download_url
        ], '~|~');
    }
}
