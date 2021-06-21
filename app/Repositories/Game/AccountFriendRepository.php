<?php

namespace App\Repositories\Game;

use App\Models\GameAccount;
use App\Models\GameAccountFriend;

/**
 * Class AccountFriendRepository
 * @package App\Repositories\Game
 */
class AccountFriendRepository
{
    /**
     * @var GameAccountFriend
     */
    protected $model;

    /**
     * AccountFriendRepository constructor.
     * @param GameAccountFriend $model
     */
    public function __construct(GameAccountFriend $model)
    {
        $this->model = $model;
    }

    /**
     * @param GameAccount|int $account
     * @param GameAccount|int $targetAccount
     */
    public function between($account, $targetAccount)
    {
        $accountID = $account instanceof GameAccount ? $account->id : $account;
        $targetAccountID = $targetAccount instanceof GameAccount ? $targetAccount->id : $targetAccount;

        return $this->model->query()
            ->where(['account' => $accountID, 'target_account' => $targetAccountID])
            ->orWhere('target_account', $accountID)->where('account', $targetAccountID);
    }
}
