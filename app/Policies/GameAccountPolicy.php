<?php

namespace App\Policies;

use App\Enums\GameAccountSettingCommentHistoryStateType;
use App\Enums\GameAccountSettingFriendRequestStateType;
use App\Enums\GameAccountSettingMessageStateType;
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
            $default = GameAccountSettingMessageStateType::fromKey('ALL');
            $mS = $receiver->setting->message_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $mS->is(GameAccountSettingMessageStateType::ALL)
            || ($mS->is(GameAccountSettingMessageStateType::FRIENDS) && $receiver->friend->has($sender->id))
            || $mS->isNot(GameAccountSettingMessageStateType::NONE);
    }

    /**
     * @param GameAccount $sender
     * @param GameAccount $receiver
     * @return bool
     */
    public function send_friend_request(GameAccount $sender, GameAccount $receiver): bool
    {
        try {
            $default = GameAccountSettingFriendRequestStateType::fromKey('ALL');
            $frS = $receiver->setting->friend_request_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return !$sender->friend->has($receiver->id) && $frS->is(GameAccountSettingFriendRequestStateType::ALL);
    }

    /**
     * @param GameAccount|null $viewer
     * @param GameAccount $target
     * @return bool
     */
    public function view_comment_history(?GameAccount $viewer, GameAccount $target): bool
    {
        try {
            $default = GameAccountSettingCommentHistoryStateType::fromKey('ALL');
            $cS = $target->setting->comment_history_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $cS->is(GameAccountSettingCommentHistoryStateType::ALL)
            || ($viewer && $cS->is(GameAccountSettingCommentHistoryStateType::FRIENDS) && $target->friend->has($viewer->id))
            || $cS->isNot(GameAccountSettingCommentHistoryStateType::NONE);
    }
}
