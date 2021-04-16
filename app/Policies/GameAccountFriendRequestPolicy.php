<?php

namespace App\Policies;

use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class GameAccountFriendRequestPolicy
 * @package App\Policies
 */
class GameAccountFriendRequestPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $operator
     * @param GameAccountFriendRequest $request
     * @return bool
     */
    public function read(GameAccount $operator, GameAccountFriendRequest $request): bool
    {
        return $operator->id === (int)$request->to_account;
    }

    /**
     * @param GameAccount $operator
     * @param GameAccountFriendRequest $request
     * @return bool
     */
    public function accept(GameAccount $operator, GameAccountFriendRequest $request): bool
    {
        return $operator->id === (int)$request->to_account;
    }
}
