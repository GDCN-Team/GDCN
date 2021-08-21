<?php

namespace App\Policies\Game;

use App\Enums\Game\Account\Setting\CommentHistoryState;
use App\Enums\Game\Account\Setting\FriendRequestState;
use App\Enums\Game\Account\Setting\MessageState;
use App\Models\Game\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function sendFriendRequest(Account $account, Account $target): bool
    {
        $isFriend = $target->friends()
            ->where([
                'account' => $target->id,
                'target_account' => $account->id
            ])->orWhere([
                'target_account' => $target->id
            ])->where([
                'account' => $account->id
            ])->exists();

        return $isFriend === false && ($target->setting?->friend_request_state->is(FriendRequestState::ALL) ?? true);
    }

    public function sendMessage(Account $account, Account $target): bool
    {
        $isFriend = $target->friends()
            ->where([
                'account' => $target->id,
                'target_account' => $account->id
            ])->orWhere([
                'target_account' => $target->id
            ])->where([
                'account' => $account->id
            ])->exists();

        return ($target->setting?->message_state->is(MessageState::ALL) ?? true)
            || ($target->setting?->message_state->is(MessageState::FRIENDS) && $isFriend === true)
            || $target->setting?->message_state->isNot(MessageState::NONE);
    }

    public function viewCommentHistory(Account $account, Account $target): bool
    {
        $isFriend = $target->friends()
            ->where([
                'account' => $target->id,
                'target_account' => $account->id
            ])->orWhere([
                'target_account' => $target->id
            ])->where([
                'account' => $account->id
            ])->exists();

        return ($target->setting?->comment_history_state->is(CommentHistoryState::ALL) ?? true)
            || ($target->setting?->comment_history_state->is(CommentHistoryState::FRIENDS) && $isFriend === true)
            || $target->setting?->comment_history_state->isNot(CommentHistoryState::NONE);
    }
}
