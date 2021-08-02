<?php

namespace App\Events;

use App\Models\Game\Level;
use App\Models\Game\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LevelDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Level $level;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Level $level)
    {
        $this->user = $user;
        $this->level = $level;
    }
}
