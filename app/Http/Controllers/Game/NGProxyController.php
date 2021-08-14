<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\SongGetException;
use App\Http\Controllers\Controller;
use App\Models\Game\Song;
use GDCN\GDObject;
use Illuminate\Support\Facades\Http;

class NGProxyController extends Controller
{
    /**
     * @throws SongGetException
     */
    public function getSong(int $songID): Song
    {
        if (!$song = Song::whereSongId($songID)->first()) {
            $response = Http::asForm()
                ->post('https://dl.geometrydashchinese.com/getGJSongInfo.php', [
                    'songID' => $songID,
                    'secret' => 'Wmfd2893gb7'
                ])->body();

            if (!$response || $response <= 0) {
                $response = Http::asForm()
                    ->post('https://dl.geometrydashchinese.com/getGJLevels21.php', [
                        'song' => $songID,
                        'customSong' => true,
                        'secret' => 'Wmfd2893gb7'
                    ])->body();

                if (!$response || $response <= 0) {
                    throw new SongGetException('歌曲获取失败');
                }
            }

            $song = GDObject::split($response, '~|~');
            return Song::create([
                'song_id' => $song[1],
                'name' => $song[2],
                'artist_id' => $song[3],
                'artist_name' => $song[4],
                'size' => $song[5],
                'video_id' => $song[6],
                'author_youtube_url' => $song[7],
                'download_url' => $song[10]
            ]);
        }

        return $song;
    }

    public function getInfo(int $songID): string
    {
        try {
            return $this->getSong($songID)->toJson();
        } catch (SongGetException $e) {
            return $e->getMessage();
        }
    }

    public function getObject(int $songID): string
    {
        try {
            $song = $this->getSong($songID);
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
        } catch (SongGetException $e) {
            return $e->getMessage();
        }
    }

    public function getTopArtists(int $page): string
    {
        return Song::forPage($page)
            ->get()
            ->map(function (Song $song) {
                return GDObject::merge([
                    4 => $song->artist_name,
                    7 => $song->author_youtube_url
                ], ':');
            })->join('|');
    }
}
