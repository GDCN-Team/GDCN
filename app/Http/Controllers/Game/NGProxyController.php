<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\Game\NGProxyService;

class NGProxyController extends Controller
{
    public function __construct(
        public NGProxyService $service
    )
    {

    }

    /**
     * @throws SongGetException
     * @throws SongNotFoundException
     */
    public function info(int $songID): string
    {
        return $this->service->getSongInfo($songID);
    }

    /**
     * @throws SongGetException
     * @throws SongNotFoundException
     */
    public function object(int $songID): string
    {
        return $this->service->getSongObjectForGD($songID);
    }
}
