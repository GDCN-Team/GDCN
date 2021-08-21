<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

class SongGetException extends Exception
{
    public function render(): int
    {
        return ResponseCode::SONG_GET_FAILED;
    }
}
