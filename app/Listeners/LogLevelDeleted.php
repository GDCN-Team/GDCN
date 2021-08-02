<?php

namespace App\Listeners;

use App\Events\LevelDeleted;
use Illuminate\Support\Facades\Log;

class LogLevelDeleted
{
    /**
     * Handle the event.
     *
     * @param LevelDeleted $event
     * @return void
     */
    public function handle(LevelDeleted $event)
    {
        Log::channel('gdcn')
            ->info('[Level]删除成功', ['userID' => $event->user->id, 'levelID' => $event->level->id]);
    }
}
