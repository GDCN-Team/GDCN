<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class InvalidArgumentException
 * @package App\Exceptions
 */
class InvalidArgumentException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::INVALID_REQUEST;
    }
}
