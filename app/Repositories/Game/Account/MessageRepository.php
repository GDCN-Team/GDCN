<?php

namespace App\Repositories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use App\Services\Game\HelperService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MessageRepository
 * @package App\Repositories\Game
 */
class MessageRepository
{
    /**
     * MessageRepository constructor.
     * @param Message $model
     * @param HelperService $helper
     */
    public function __construct(
        protected Message $model,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return Builder|Message
     */
    public function between(Account|int $account, Account|int $targetAccount): Builder|Message
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        return $this->model->query()
            ->where(['account' => $accountID, 'to_account' => $targetAccountID])
            ->orWhere('to_account', $accountID)->where('account', $targetAccountID);
    }

    /**
     * @param int|Account $account
     * @param bool $getSent
     * @return Message|Builder
     */
    public function getByRelation(Account|int $account, bool $getSent): Builder|Message
    {
        return $this->model->query()
            ->where($getSent ? 'account' : 'to_account', $this->helper->getID($account));
    }
}
