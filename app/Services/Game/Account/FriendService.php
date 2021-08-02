<?php

namespace App\Services\Game\Account;

use App\Events\FriendRemoved;
use App\Models\Game\Account;
use App\Repositories\Game\Account\FriendRepository;

/**
 * Class FriendService
 * @package App\Services\Game\Account
 */
class FriendService
{
    /**
     * FriendService constructor.
     * @param FriendRepository $repository
     */
    public function __construct(
        protected FriendRepository $repository
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return mixed
     */
    public function remove(Account|int $account, Account|int $targetAccount): mixed
    {
        $friend = $this->repository->between($account, $targetAccount)->first();
        if (!$result = $friend->delete()) {
            return $result;
        }

        event(new FriendRemoved($friend));
        return $result;
    }
}
