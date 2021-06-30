<?php

namespace App\Exceptions\Game\Request;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class GameRequestAuthorizeException
 * @package App\Exceptions
 */
class AuthorizationException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::AUTH_FAILED;
    }
}
