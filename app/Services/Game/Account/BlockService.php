<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use App\Repositories\Game\Account\FriendRepository;
use App\Repositories\Game\Account\FriendRequestRepository;
use App\Repositories\Game\Account\MessageRepository;
use App\Services\Game\HelperService;
use Illuminate\Support\Facades\Log;

/**
 * Class BlockService
 * @package App\Services\Game
 */
class BlockService
{
    /**
     * BlockService constructor.
     * @param HelperService $helper
     * @param FriendRepository $friendRepository
     * @param MessageRepository $messageRepository
     * @param FriendRequestRepository $friendRequestRepository
     */
    public function __construct(
        protected HelperService $helper,
        protected FriendRepository $friendRepository,
        protected MessageRepository $messageRepository,
        protected FriendRequestRepository $friendRequestRepository
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return Block
     */
    public function block(Account|int $account, Account|int $targetAccount): Block
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        // Delete messages, friend & friend requests
        $this->friendRepository->between($accountID, $targetAccountID)->delete();
        Log::debug("[Account Block] 正在删除好友", [
            'accountID' => $accountID,
            'targetAccountID' => $targetAccountID
        ]);

        $this->messageRepository->between($accountID, $targetAccountID)->delete();
        Log::debug("[Account Block] 正在删除消息", [
            'accountID' => $accountID,
            'targetAccountID' => $targetAccountID
        ]);

        $this->friendRequestRepository->between($accountID, $targetAccountID)->delete();
        Log::debug("[Account Block] 正在删除好友请求", [
            'accountID' => $accountID,
            'targetAccountID' => $targetAccountID
        ]);


        $block = new Block();
        $block->account = $accountID;
        $block->target_account = $targetAccountID;
        $block->save();

        Log::debug("[Account Block] 拉黑成功", [
            'accountID' => $accountID,
            'targetAccountID' => $targetAccountID
        ]);

        return $block;
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @return mixed
     */
    public function unblock(Account|int $account, Account|int $targetAccount): mixed
    {
        return Block::where([
            'account' => $this->helper->getID($account),
            'target_account' => $this->helper->getID($targetAccount)
        ])->delete();
    }
}
