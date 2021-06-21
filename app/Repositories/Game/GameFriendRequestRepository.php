<?php

namespace App\Repositories\Game;

use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;

/**
 * Class GameFriendRequestRepository
 * @package App\Repositories\Game
 */
class GameFriendRequestRepository
{
    /**
     * @var GameAccountFriendRequest
     */
    protected $model;

    /**
     * GameFriendRequestRepository constructor.
     * @param GameAccountFriendRequest $model
     */
    public function __construct(GameAccountFriendRequest $model)
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
            ->where(['account' => $accountID, 'to_account' => $targetAccountID])
            ->orWhere('to_account', $accountID)->where('account', $targetAccountID);
    }
}
