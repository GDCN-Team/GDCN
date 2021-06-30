<?php

namespace App\Services\Game;

use App\Models\GameAccount;
use App\Models\GameAccountBlock;
use App\Repositories\Game\AccountFriendRepository;
use App\Repositories\Game\AccountMessageRepository;
use App\Repositories\Game\FriendRequestRepository;

/**
 * Class AccountBlockService
 * @package App\Services\Game
 */
class AccountBlockService
{
    /**
     * @var AccountFriendRepository
     */
    protected $friendRepository;

    /**
     * @var AccountMessageRepository
     */
    protected $messageRepository;

    /**
     * @var FriendRequestRepository
     */
    protected $friendRequestRepository;

    /**
     * AccountBlockService constructor.
     * @param AccountFriendRepository $friendRepository
     * @param AccountMessageRepository $messageRepository
     * @param FriendRequestRepository $friendRequestRepository
     */
    public function __construct(AccountFriendRepository $friendRepository, AccountMessageRepository $messageRepository, FriendRequestRepository $friendRequestRepository)
    {
        $this->friendRepository = $friendRepository;
        $this->messageRepository = $messageRepository;
        $this->friendRequestRepository = $friendRequestRepository;
    }

    /**
     * @param GameAccount|int $account
     * @param GameAccount|int $targetAccount
     * @return GameAccountBlock
     */
    public function block($account, $targetAccount): GameAccountBlock
    {
        $accountID = $account instanceof GameAccount ? $account->id : $account;
        $targetAccountID = $targetAccount instanceof GameAccount ? $targetAccount->id : $targetAccount;

        //Delete messages & friend & friend requests
        $this->friendRepository->between($account, $targetAccountID)->delete();
        $this->messageRepository->between($account, $targetAccountID)->delete();
        $this->friendRequestRepository->between($account, $targetAccountID)->delete();

        $block = new GameAccountBlock;
        $block->account = $accountID;
        $block->target_account = $targetAccountID;
        $block->save();

        return $block;
    }
}
