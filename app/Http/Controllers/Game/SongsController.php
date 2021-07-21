<?php

namespace App\Http\Controllers\Game;

use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Song\GetRequest;
use App\Http\Requests\Game\Song\TopArtistsGetRequest;
use App\Models\Game\CustomSong;
use App\Models\Game\Song;
use GDCN\GDObject;
use Illuminate\Support\Facades\Http;

/**
 * Class SongsController
 * @package App\Http\Controllers
 */
class SongsController extends Controller
{
    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJSongInfo
     */
    public function get(GetRequest $request)
    {
        $data = $request->validated();
        $song = $data['songID'] >= config('game.customSongIdOffset', 5000000) ? CustomSong::whereSongId($data['songID'])->first() : Song::whereId($data['songID'])->first();

        if ($song instanceof Song || $song instanceof CustomSong) {
            return $song->toSongString();
        }

        $response = Http::get('http://ng.geometrydashchinese.com/api', [
            'method' => 'object',
            'songID' => $data['songID'],
            'give_cdn' => true
        ])->body();

        $songInfo = GDObject::split($response, '~|~');
        $song = Song::query()
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
     * @param TopArtistsGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJTopArtists
     */
    public function getTopArtists(TopArtistsGetRequest $request): string
    {
        $data = $request->validated();

        $page = $data['page'];
        $perPage = app(Helpers::class)->perPage;

        return Song::query()
            ->forPage(++$page, $perPage)
            ->get()
            ->map(function (Song $song) {
                return GDObject::merge([
                    4 => $song->author_name
                ], ':');
            })->join('|');
    }
}
