<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;
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
        return is_numeric($this->message) ? $this->message : ResponseCode::AUTH_FAILED;
    }
}
