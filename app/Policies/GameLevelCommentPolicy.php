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
     * @param int $levelID
     * @return bool
     */
    public function delete(GameAccount $account, GameLevelComment $comment, int $levelID): bool
    {
        return $account->id === $comment->account && $comment->level === $levelID;
    }
}
