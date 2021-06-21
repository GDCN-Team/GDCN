<?php

namespace App\Repositories\Game;

use App\Models\GameAccount;
use App\Models\GameAccountMessage;

/**
 * Class AccountMessageRepository
 * @package App\Repositories\Game
 */
class AccountMessageRepository
{
    /**
     * @var GameAccountMessage
     */
    protected $model;

    /**
     * AccountMessageRepository constructor.
     * @param GameAccountMessage $model
     */
    public function __construct(GameAccountMessage $model)
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
