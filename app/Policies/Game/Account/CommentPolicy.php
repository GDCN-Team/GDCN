<?php

namespace App\Policies\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @param Comment $comment
     * @return bool
     */
    public function delete(Account $account, Comment $comment): bool
    {
        return $comment->account === $account->id;
    }
}
