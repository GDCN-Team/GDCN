<?php

namespace App\Services\Game;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Http\Controllers\Game\NGProxyController;
use App\Models\Game\CustomSong;
use GDCN\GDObject;

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
     * @throws SongNotFoundException
     * @throws SongGetException
     */
    public function get(int $songID): string
    {
        $offset = config('game.customSongIdOffset');
        if ($songID >= $offset) {
            if (!$song = CustomSong::whereSongId($songID)->first()) {
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

        return $this->NGProxy->object($songID);
    }

    /**
     * @param int $page
     * @return string
     */
    public function getTopArtists(int $page): string
    {
        # TODO: Fix top artists
        return $this->NGProxy->getTopArtists($page);
    }
}
