<?php

namespace App\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class GameRequestAuthorizeException
 * @package App\Exceptions
 */
class GameRequestAuthorizationException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::AUTH_FAILED;
    }
}
