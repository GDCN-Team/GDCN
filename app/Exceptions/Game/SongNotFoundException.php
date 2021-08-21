<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

class SongNotFoundException extends Exception
{
    public function render(): int
    {
        return ResponseCode::SONG_NOT_FOUND;
    }
}
