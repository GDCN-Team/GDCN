<?php

namespace App\Listeners;

use App\Events\FriendAdded;
use Illuminate\Support\Facades\Log;

class LogFriendAdded
{
    /**
     * Handle the event.
     *
     * @param FriendAdded $event
     * @return void
     */
    public function handle(FriendAdded $event)
    {
        $friend = $event->friend;
        Log::channel('gdcn')
            ->info('[Account Friend]添加成功', ['accountID' => $friend->account, 'targetAccountID' => $friend->target_account]);
    }
}
