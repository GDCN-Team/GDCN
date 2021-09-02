<?php

namespace App\Services\Game;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Http\Controllers\Game\NGProxyController;
use App\Models\Game\CustomSong;
use App\Models\NGProxy\Song;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class SongService
{
    public function __construct(
        public NGProxyController $NGProxy
    )
    {
    }

    /**
     * @throws SongGetException
     * @throws SongNotFoundException
     */
    public function get(int $songID): string
    {
        Log::channel('gdcn')
            ->info('[Song System] Action: Get Song', [
                'songID' => $songID
            ]);

        $customSongOffset = config('game.customSongIdOffset');
        if ($songID >= $customSongOffset) {
            $song = CustomSong::whereSongId($songID)->first();

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

    public function getTopArtists(int $page): string
    {
        Log::channel('gdcn')
            ->info('[Song System] Action: Get Top Artists', [
                'page' => $page
            ]);

        $result = Song::forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (Song $song) {
                return GDObject::merge([
                    4 => $song->artist_name,
                    7 => $song->author_youtube_url
                ], ':');
            })->join('|');

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate(Song::count(), $page)
        ]);
    }
}
