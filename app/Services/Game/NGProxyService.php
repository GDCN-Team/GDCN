<?php

namespace App\Services\Game;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Models\NGProxy\Song;
use GDCN\GDObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NGProxyService
{
    public function __construct(
        public GDProxyService $GDProxy
    )
    {
    }

    /**
     * @throws SongGetException
     */
    public function getSong(int $songID, bool $getFromApi = true): Song
    {
        $song = $this->getSongModel($songID);
        if (empty($song)) {
            if ($getFromApi) {
                $songObjectData = $this->getFromSongInfoApi($songID);
                if (empty($songObjectData) || $songObjectData <= 0) {
                    if ($songObjectData === '-2') {
                        $disabled = true;
                    }

                    $songObjectData = $this->getFromGetLevelsApi($songID);
                    if (empty($songObjectData) || $songObjectData <= 0) {
                        throw new SongGetException('[NGProxy] Failed to get song.');
                    }
                }

                $songObject = GDObject::split($songObjectData, '~|~');
                if (empty($songObject[1]) || empty($songObject[10])) {
                    throw new SongGetException('[NGProxy] Song info missing.');
                }

                return $this->saveSongFromObject($songObject, $disabled ?? false);
            } else {
                throw new SongGetException('[NGProxy] Failed to get song.');
            }
        }

        return $song;
    }

    /**
     * @throws SongGetException
     */
    public function getSongInfo(int $songID): string
    {
        return $this->getSong($songID)->toJson();
    }

    /**
     * @throws SongGetException
     */
    public function getSongObjectForGD(int $songID): string
    {
        $song = $this->getSong($songID);
        return $this->convertSongModelToObject($song);
    }

    /**
     * @throws SongGetException
     */
    public function getSongObjectForGDProxy(int $songID): string
    {
        $song = $this->getSong($songID, false);
        return $this->convertSongModelToObject($song);
    }

    protected function getSongModel(int $songID): ?Song
    {
        return Song::whereSongId($songID)->first();
    }

    /**
     * @throws SongNotFoundException
     */
    protected function saveSongFromObject(array $songObject, bool $disabled = false): Song
    {
        if (!$this->checkObjectExistsInOss($songObject[1])) {
            if ($this->downloadSongThenSaveToOss($songObject[1], $songObject[10])) {
                $songObject[10] = $this->getFreeDownloadUrl($songObject[1]);
            }
        }

        return Song::create([
            'song_id' => $songObject[1],
            'name' => $songObject[2],
            'artist_id' => $songObject[3],
            'artist_name' => $songObject[4],
            'size' => $songObject[5],
            'video_id' => $songObject[6] ?? null,
            'author_youtube_url' => $songObject[7] ?? null,
            'download_link' => $songObject[10],
            'disabled' => $disabled
        ]);
    }

    protected function convertSongModelToObject(Song $song): string
    {
        return GDObject::merge([
            1 => $song->song_id,
            2 => $song->name,
            3 => $song->artist_id,
            4 => $song->artist_name,
            5 => $song->size,
            6 => $song->video_id,
            7 => $song->author_youtube_url,
            10 => $song->download_link
        ], '~|~');
    }

    protected function getFromSongInfoApi(int $songID, string $uri = '/getGJSongInfo.php'): string
    {
        $response = $this->GDProxy->proxy($uri, [
            'songID' => $songID,
            'secret' => 'Wmfd2893gb7'
        ]);

        Log::debug("[NGProxy] Requested $uri", [
            'data' => $response
        ]);

        return $response;
    }

    protected function getFromGetLevelsApi(int $songID, string $uri = '/getGJLevels21.php'): string
    {
        $response = $this->GDProxy->proxy($uri, [
            'song' => $songID,
            'customSong' => true,
            'secret' => 'Wmfd2893gb7'
        ]);

        Log::debug("[NGProxy] Requested $uri", [
            'data' => $response
        ]);

        return explode('#', $response)[2];
    }

    protected function getObjectNameForOss(int $songID, string $prefix = 'ngproxy/songs'): string
    {
        return $prefix . '/' . $songID . '_' . sha1('NGProxy' . $songID) . '.mp3';
    }

    protected function checkObjectExistsInOss(int $songID): bool
    {
        $object = $this->getObjectNameForOss($songID);
        return Storage::disk('oss')->exists($object);
    }

    protected function downloadSongThenSaveToOss(int $songID, string $url = null): bool|string
    {
        $songBinaryData = $this->GDProxy
            ->proxy
            ->getProxyInstance()
            ->get($url);

        $object = $this->getObjectNameForOss($songID);
        return Storage::disk('oss')->put($object, $songBinaryData);
    }

    /**
     * @throws SongNotFoundException
     */
    protected function getFreeDownloadUrl(int $songID, string $domain = 'https://cdn.geometrydashchinese.com'): string
    {
        if (!$this->checkObjectExistsInOss($songID)) {
            throw new SongNotFoundException('Song not found in oss.');
        }

        $object = $this->getObjectNameForOss($songID);
        return $domain . '/' . $object;
    }
}
