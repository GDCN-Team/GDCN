<?php

namespace App\Policies;

use App\Enums\Game\AccountSettingCommentHistoryStateType;
use App\Enums\Game\AccountSettingFriendRequestStateType;
use App\Enums\Game\AccountSettingMessageStateType;
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
            $default = AccountSettingMessageStateType::fromKey('ALL');
            $mS = $receiver->setting->message_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $mS->is(AccountSettingMessageStateType::ALL)
            || ($mS->is(AccountSettingMessageStateType::FRIENDS) && $receiver->friend->has($sender->id))
            || $mS->isNot(AccountSettingMessageStateType::NONE);
    }

    /**
     * @param GameAccount $sender
     * @param GameAccount $receiver
     * @return bool
     */
    public function send_friend_request(GameAccount $sender, GameAccount $receiver): bool
    {
        try {
            $default = AccountSettingFriendRequestStateType::fromKey('ALL');
            $frS = $receiver->setting->friend_request_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return !$sender->friend->has($receiver->id) && $frS->is(AccountSettingFriendRequestStateType::ALL);
    }

    /**
     * @param GameAccount|null $viewer
     * @param GameAccount $target
     * @return bool
     */
    public function view_comment_history(?GameAccount $viewer, GameAccount $target): bool
    {
        try {
            $default = AccountSettingCommentHistoryStateType::fromKey('ALL');
            $cS = $target->setting->comment_history_state ?? $default;
        } catch (InvalidEnumKeyException $e) {
            return false;
        }

        return $cS->is(AccountSettingCommentHistoryStateType::ALL)
            || ($viewer && $cS->is(AccountSettingCommentHistoryStateType::FRIENDS) && $target->friend->has($viewer->id))
            || $cS->isNot(AccountSettingCommentHistoryStateType::NONE);
    }
}
