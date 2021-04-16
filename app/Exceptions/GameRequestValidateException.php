<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;
use Exception;

/**
 * Class GameRequestValidateException
 * @package App\Exceptions
 */
class GameRequestValidateException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return is_numeric($this->message) ? $this->message : ResponseCode::REQUEST_CHECK_FAILED;
    }
}
