<?php

namespace App\Policies;

use App\Enums\Game\Account\Setting\CommentHistoryState;
use App\Enums\Game\Account\Setting\FriendRequestState;
use App\Enums\Game\Account\Setting\MessageState;
use App\Models\GameAccount;
use BenSampo\Enum\Exceptions\InvalidEnumKeyException;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameAccountPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $sender
     * @param GameAccount $receiver
     * @return bool
     */
    public function send_message(GameAccount $sender, GameAccount $receiver): bool
    {
        try {
            $default = MessageState::fromKey('ALL');
            $mS = $receiver->setting->message_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $mS->is(MessageState::ALL)
            || ($mS->is(MessageState::FRIENDS) && $receiver->friend->has($sender->id))
            || $mS->isNot(MessageState::NONE);
    }

    /**
     * @param GameAccount $sender
     * @param GameAccount $receiver
     * @return bool
     */
    public function send_friend_request(GameAccount $sender, GameAccount $receiver): bool
    {
        try {
            $default = FriendRequestState::fromKey('ALL');
            $frS = $receiver->setting->friend_request_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return !$sender->friend->has($receiver->id) && $frS->is(FriendRequestState::ALL);
    }

    /**
     * @param GameAccount|null $viewer
     * @param GameAccount $target
     * @return bool
     */
    public function view_comment_history(?GameAccount $viewer, GameAccount $target): bool
    {
        try {
            $default = CommentHistoryState::fromKey('ALL');
            $cS = $target->setting->comment_history_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $cS->is(CommentHistoryState::ALL)
            || ($viewer && $cS->is(CommentHistoryState::FRIENDS) && $target->friend->has($viewer->id))
            || $cS->isNot(CommentHistoryState::NONE);
    }
}
