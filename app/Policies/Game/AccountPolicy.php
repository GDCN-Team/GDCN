<?php

namespace App\Policies\Game;

use App\Enums\Game\Account\Setting\CommentHistoryState;
use App\Enums\Game\Account\Setting\FriendRequestState;
use App\Enums\Game\Account\Setting\MessageState;
use App\Models\Game\Account;
use App\Repositories\Game\Account\FriendRepository;
use BenSampo\Enum\Exceptions\InvalidEnumKeyException;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * @var FriendRepository
     */
    protected $friendRepository;

    /**
     * AccountPolicy constructor.
     * @param FriendRepository $friendRepository
     */
    public function __construct(FriendRepository $friendRepository)
    {
        $this->friendRepository = $friendRepository;
    }

    /**
     * @param Account $sender
     * @param Account $receiver
     * @return bool
     */
    public function send_message(Account $sender, Account $receiver): bool
    {
        try {
            $default = MessageState::fromKey('ALL');
            $mS = $receiver->setting->message_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $mS->is(MessageState::ALL)
            || ($mS->is(MessageState::FRIENDS) && $this->friendRepository->between($sender, $sender)->exists())
            || $mS->isNot(MessageState::NONE);
    }

    /**
     * @param Account $sender
     * @param Account $receiver
     * @return bool
     */
    public function send_friend_request(Account $sender, Account $receiver): bool
    {
        try {
            $default = FriendRequestState::fromKey('ALL');
            $frS = $receiver->setting->friend_request_state ?? $default;
        } catch (InvalidEnumKeyException) {
            return false;
        }

        return $this->friendRepository->between($sender->id, $receiver->id)->doesntExist()
            && $frS->is(FriendRequestState::ALL);
    }

    /**
     * @param Account $target
     * @return bool
     */
    public function view_comment_history(/* ?Account $viewer, */ Account $target): bool
    {
        try {
            $default = CommentHistoryState::fromKey('ALL');
            $cS = $target->setting->comment_history_state ?? $default;
        } catch (InvalidEnumKeyException) {
            return false;
        }

        return $cS->is(CommentHistoryState::ALL)
            // || ($viewer && $cS->is(CommentHistoryState::FRIENDS) && $this->friendRepository->between($viewer, $target))
            || $cS->isNot(CommentHistoryState::NONE);
    }
}
