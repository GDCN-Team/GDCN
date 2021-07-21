<?php

namespace App\Policies\Game;

use App\Models\Game\Account;
use App\Models\Game\Level\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class LevelCommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @param Comment $comment
     * @return bool
     */
    public function delete(Account $account, Comment $comment): bool
    {
        return $account->id === (int)$comment->account;
    }
}
