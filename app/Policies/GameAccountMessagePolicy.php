<?php

namespace App\Policies;

use App\Models\GameAccount;
use App\Models\GameAccountMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameAccountMessagePolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $operator
     * @param GameAccountMessage $message
     * @return bool
     */
    public function download(GameAccount $operator, GameAccountMessage $message): bool
    {
        return (int)$message->account === $operator->id || (int)$message->to_account === $operator->id;
    }

    /**
     * @param GameAccount $operator
     * @param GameAccountMessage $message
     * @return bool
     */
    public function delete(GameAccount $operator, GameAccountMessage $message): bool
    {
        return (int)$message->account === $operator->id || (int)$message->to_account === $operator->id;
    }
}
