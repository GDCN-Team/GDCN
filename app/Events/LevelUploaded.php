<?php

namespace App\Events;

use App\Models\Game\Level;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LevelUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Level $level;
    public bool $update;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Level $level, bool $update)
    {
        $this->level = $level;
        $this->update = $update;
    }
}
