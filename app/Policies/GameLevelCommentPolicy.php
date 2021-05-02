<?php

namespace App\Policies;

use App\Models\GameAccount;
use App\Models\GameLevelComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameLevelCommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $account
     * @param GameLevelComment $comment
     * @return bool
     */
    public function delete(GameAccount $account, GameLevelComment $comment): bool
    {
        return $account->id === (int)$comment->account;
    }
}
