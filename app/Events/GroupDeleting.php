<?php

namespace App\Events;

use App\Models\Game\Account\Permission\Group;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
