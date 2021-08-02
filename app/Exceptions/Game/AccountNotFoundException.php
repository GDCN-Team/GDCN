<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;

class AccountNotFoundException extends Exception
{
    /**
     * @return int
     */
    public function render(): int
    {
        return ResponseCode::ACCOUNT_NOT_FOUND;
    }
}
