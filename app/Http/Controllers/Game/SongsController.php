<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Song\GetRequest;
use App\Http\Requests\Game\Song\TopArtistsGetRequest;
use App\Services\Game\SongService;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\Proxy\Exceptions\ProxyFailedException;

/**
 * Class SongsController
 * @package App\Http\Controllers
 */
class SongsController extends Controller
{
    public function __construct(
        public SongService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJSongInfo
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            if ($data['songID'] < config('game.customSongIdOffset', 5000000)) {
                return $this->service->get($data['songID']);
            }
        } catch (SongGetException | ProxyFailedException) {
            return ResponseCode::SONG_GET_FAILED;
        }
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
        return $this->service->getTopArtists($data['page']);
    }
}
