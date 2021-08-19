<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\SongGetException;
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
     */
    public function info(int $songID): string
    {
        return $this->service->getSongInfo($songID);
    }

    /**
     * @throws SongGetException
     */
    public function object(int $songID): string
    {
        return $this->service->getSongObjectForGD($songID);
    }
}
