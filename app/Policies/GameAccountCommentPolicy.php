<?php

namespace App\Policies;

use App\Models\GameAccount;
use App\Models\GameAccountComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameAccountCommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $account
     * @param GameAccountComment $comment
     * @return bool
     */
    public function delete(GameAccount $account, GameAccountComment $comment): bool
    {
        return $comment->account === $account->id;
    }
}
