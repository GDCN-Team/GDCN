<?php

namespace App\Repositories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use App\Services\Game\HelperService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FriendRepository
 * @package App\Repositories\Game
 */
class FriendRepository
{
    /**
     * FriendRepository constructor.
     * @param Friend $model
     * @param HelperService $helper
     */
    public function __construct(
        protected Friend $model,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return Friend|Builder
     */
    public function between(Account|int $account, Account|int $targetAccount): Friend|Builder
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        return $this->model->query()
            ->where(['account' => $accountID, 'target_account' => $targetAccountID])
            ->orWhere('target_account', $accountID)->where('account', $targetAccountID);
    }
}
