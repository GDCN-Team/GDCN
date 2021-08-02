<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

/**
 * Class UserNotFoundException
 * @package App\Exceptions
 */
class UserNotFoundException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::USER_NOT_FOUND;
    }
}
