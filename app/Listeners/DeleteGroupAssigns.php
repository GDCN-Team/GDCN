<?php

namespace App\Listeners;

use App\Events\GroupDeleting;
use App\Models\Game\Account\Permission\Assign as AccountAssign;
use App\Models\Game\Account\Permission\FlagAssign;
use JetBrains\PhpStorm\NoReturn;

class DeleteGroupAssigns
{
    #[NoReturn] public function handle(GroupDeleting $event)
    {
        FlagAssign::whereGroup($event->group->id)->delete();
        AccountAssign::whereGroup($event->group->id)->delete();
    }
}
