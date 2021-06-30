<?php

namespace App\Exceptions\Game\Request;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class AuthenticationException
 * @package App\Exceptions
 */
class AuthenticationException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::AUTH_FAILED;
    }
}
