<?php

namespace App\Listeners;

use App\Events\FriendRemoved;
use Illuminate\Support\Facades\Log;

class LogFriendRemoved
{
    /**
     * Handle the event.
     *
     * @param FriendRemoved $event
     * @return void
     */
    public function handle(FriendRemoved $event)
    {
        $friend = $event->friend;
        Log::channel('gdcn')
            ->info('[Account Friend]删除成功', ['accountID' => $friend->account, 'targetAccountID' => $friend->target_account]);
    }
}
