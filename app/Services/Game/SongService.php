<?php

namespace App\Services\Game;

use App\Exceptions\Game\SongNotFoundException;
use App\Models\Game\CustomSong;
use GDCN\GDObject;
use Modules\NGProxy\Exceptions\SongDisabledException;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\NGProxy\Http\Controllers\NGProxyController;
use Modules\Proxy\Exceptions\ProxyFailedException;

/**
 * Class SongService
 * @package App\Services\Game
 */
class SongService
{
    public function __construct(
        public NGProxyController $NGProxy
    )
    {
    }

    /**
     * @param int $songID
     * @return string
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongNotFoundException
     */
    public function get(int $songID): string
    {
        // 自定义歌曲
        $offset = config('game.customSongIdOffset');
        if ($songID >= $offset) {
            $song = CustomSong::whereSongId($songID)->first();
            if (!$song) {
                throw new SongNotFoundException();
            }

            return GDObject::merge([
                1 => $song->song_id,
                2 => $song->name,
                3 => 7,
                4 => $song->author_name,
                5 => $song->size,
                10 => urlencode($song->download_url)
            ], '~|~');
        }

        return $this->NGProxy->getObject($songID);
    }

    /**
     * @param int $page
     * @return string
     */
    public function getTopArtists(int $page): string
    {
        return $this->NGProxy->getTopArtists($page);
    }
}
