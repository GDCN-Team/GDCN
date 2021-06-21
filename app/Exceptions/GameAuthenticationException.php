<?php

namespace App\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class GameAuthenticationException
 * @package App\Exceptions
 */
class GameAuthenticationException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::AUTH_FAILED;
    }
}
