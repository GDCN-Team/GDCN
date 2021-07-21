<?php

namespace App\Repositories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use App\Services\Game\HelperService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CommentRepository
 * @package App\Repositories\Game\Account
 */
class CommentRepository
{
    /**
     * CommentRepository constructor.
     * @param Comment $model
     * @param HelperService $helper
     */
    public function __construct(
        protected Comment $model,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @return Comment|Builder
     */
    public function byAccount(Account|int $account): Comment|Builder
    {
        return $this->model->whereAccount($this->helper->getID($account));
    }
}
