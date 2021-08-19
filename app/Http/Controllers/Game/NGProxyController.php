<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\SongGetException;
use App\Http\Controllers\Controller;
use App\Models\NGProxy\Song;
use GDCN\GDObject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NGProxyController extends Controller
{
    /**
     * @throws SongGetException
     */
    public function getSong(int $songID, bool $getFromOfficialServer = true): ?Song
    {
        if (!$song = Song::whereSongId($songID)->first()) {
            if ($getFromOfficialServer === true) {
                $response = Http::asForm()
                    ->post('https://dl.geometrydashchinese.com/getGJSongInfo.php', [
                        'songID' => $songID,
                        'secret' => 'Wmfd2893gb7'
                    ])->body();

                Log::debug('NGProxy response', [
                    'data' => $response
                ]);

                if (!$response || $response <= 0) {
                    $response = Http::asForm()
                        ->post('https://dl.geometrydashchinese.com/getGJLevels21.php', [
                            'song' => $songID,
                            'customSong' => true,
                            'secret' => 'Wmfd2893gb7'
                        ])->body();

                    Log::debug('NGProxy response', [
                        'data' => $response
                    ]);

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
                    'video_id' => $song[6] ?? null,
                    'author_youtube_url' => $song[7] ?? null,
                    'download_link' => $song[10],
                    'disabled' => false
                ]);
            } else {
                throw new SongGetException('歌曲获取失败');
            }
        }

        return $song;
    }

    /**
     * @throws SongGetException
     */
    public function getInfo(int $songID): string
    {
        return $this->getSong($songID)->toJson();
    }

    /**
     * @throws SongGetException
     */
    public function getObject(int $songID, bool $getFromOfficialServer = true): string
    {
        $song = $this->getSong($songID, $getFromOfficialServer);
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
