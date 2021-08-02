<?php

namespace App\Events;

use App\Models\Game\Account\Friend;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Friend $friend;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }
}
