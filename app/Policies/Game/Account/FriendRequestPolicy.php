<?php

namespace App\Policies\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class FriendRequestPolicy
 * @package App\Policies
 */
class FriendRequestPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $operator
     * @param FriendRequest $request
     * @return bool
     */
    public function read(Account $operator, FriendRequest $request): bool
    {
        return $operator->id === $request->to_account;
    }

    /**
     * @param Account $operator
     * @param FriendRequest $request
     * @return bool
     */
    public function accept(Account $operator, FriendRequest $request): bool
    {
        return $operator->id === $request->to_account;
    }

    /**
     * @param Account $target
     * @param FriendRequest $request
     * @return bool
     */
    public function add(Account $target, FriendRequest $request): bool
    {
        return $target->id === $request->account;
    }
}
