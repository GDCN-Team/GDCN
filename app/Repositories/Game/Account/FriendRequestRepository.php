<?php

namespace App\Repositories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use App\Services\Game\HelperService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FriendRequestRepository
 * @package App\Repositories\Game
 */
class FriendRequestRepository
{
    /**
     * FriendRequestRepository constructor.
     * @param FriendRequest $model
     * @param HelperService $helper
     */
    public function __construct(
        protected FriendRequest $model,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return FriendRequest|Builder
     */
    public function between(Account|int $account, Account|int $targetAccount): Builder|FriendRequest
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        return $this->model->query()
            ->where(['account' => $accountID, 'to_account' => $targetAccountID])
            ->orWhere('to_account', $accountID)->where('account', $targetAccountID);
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @param bool $isSender
     * @return FriendRequest|Builder
     */
    public function betweenByRelation(Account|int $account, Account|int $targetAccount, bool $isSender): Builder|FriendRequest
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        if ($isSender) {
            return $this->model->where([
                'account' => $accountID,
                'to_account' => $targetAccountID
            ]);
        }

        return $this->model->where([
            'account' => $targetAccountID,
            'to_account' => $accountID
        ]);
    }

    /**
     * @param int|Account $account
     * @param bool $getSent
     * @return FriendRequest|Builder
     */
    public function byAccount(Account|int $account, bool $getSent): Builder|FriendRequest
    {
        return $this->model->where([$getSent ? 'account' : 'to_account' => $this->helper->getID($account)]);
    }
}
