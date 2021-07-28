<?php

namespace App\Services\Game;

use Modules\NGProxy\Exceptions\SongGetException;
use Modules\NGProxy\Http\Controllers\NGProxyController;
use Modules\Proxy\Exceptions\ProxyFailedException;

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
     * @throws SongGetException
     * @throws ProxyFailedException
     */
    public function get(int $songID): string
    {
        return $this->NGProxy->getObject($songID);
    }

    /**
     * @param int $page
     * @return string
     */
    public function getTopArtists(int $page): string
    {
        return $this->NGProxy->getTopArtists($page);
    }
}
