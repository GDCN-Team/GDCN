<?php

namespace App\Policies\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $operator
     * @param Message $message
     * @param bool $isSender
     * @return bool
     */
    public function download(Account $operator, Message $message, bool $isSender): bool
    {
        return ($isSender ? $message->account : $message->to_account) === $operator->id;
    }

    /**
     * @param Account $operator
     * @param Message $message
     * @param bool $isSender
     * @return bool
     */
    public function delete(Account $operator, Message $message, bool $isSender): bool
    {
        return $this->download($operator, $message, $isSender);
    }
}
