<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Song\GetRequest;
use App\Http\Requests\Game\Song\TopArtistsGetRequest;
use App\Services\Game\SongService;

class SongsController extends Controller
{
    public function __construct(
        public SongService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJSongInfo
     * @throws SongGetException
     * @throws SongNotFoundException
     */
    public function get(GetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->get($data['songID']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJTopArtists
     */
    public function getTopArtists(TopArtistsGetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->getTopArtists($data['page']);
    }
}
