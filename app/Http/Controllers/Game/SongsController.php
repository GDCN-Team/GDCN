<?php

namespace App\Http\Controllers\Game;

use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameSongGetRequest;
use App\Http\Requests\GameTopArtistsGetRequest;
use App\Models\GameCustomSong;
use App\Models\GameSong;
use GDCN\GDObject;
use Illuminate\Support\Facades\Http;

/**
 * Class SongsController
 * @package App\Http\Controllers
 */
class SongsController extends Controller
{
    /**
     * @param GameSongGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJSongInfo
     */
    public function get(GameSongGetRequest $request)
    {
        $data = $request->validated();
        $song = $data['songID'] >= config('game.customSongIdOffset', 5000000) ? GameCustomSong::whereSongId($data['songID'])->first() : GameSong::whereId($data['songID'])->first();

        if ($song instanceof GameSong || $song instanceof GameCustomSong) {
            return $song->toSongString();
        }

        $response = Http::get('http://ng.geometrydashchinese.com/api', [
            'method' => 'object',
            'songID' => $data['songID']
        ])->body();

        $songInfo = GDObject::split($response, '~|~');
        $song = GameSong::query()
            ->updateOrCreate([
                'id' => $songInfo[1]
            ], [
                'name' => $songInfo[2],
                'author_id' => $songInfo[3],
                'author_name' => $songInfo[4],
                'size' => $songInfo[5],
                'download_url' => $songInfo[10],
                'disabled' => false
            ]);

        return $song->toSongString();
    }

    /**
     * @param GameTopArtistsGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJTopArtists
     */
    public function getTopArtists(GameTopArtistsGetRequest $request): string
    {
        $data = $request->validated();

        $page = $data['page'];
        $perPage = app(Helpers::class)->perPage;

        return GameSong::query()
            ->forPage(++$page, $perPage)
            ->get()
            ->map(function (GameSong $song) {
                return GDObject::merge([
                    4 => $song->author_name
                ], ':');
            })->join('|');
    }
}
