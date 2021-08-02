<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

class ChallengeGenerateException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::CHALLENGE_GET_FAILED;
    }
}
