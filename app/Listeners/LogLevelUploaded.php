<?php

namespace App\Listeners;

use App\Events\LevelUploaded;
use Illuminate\Support\Facades\Log as LogFacade;

class LogLevelUploaded
{
    /**
     * Handle the event.
     *
     * @param LevelUploaded $event
     * @return void
     */
    public function handle(LevelUploaded $event)
    {
        $level = $event->level;
        LogFacade::channel('gdcn')
            ->info('[Level]' . $event->update ? '更新' : '上传' . '成功', ['userID' => $level->user, 'levelID' => $level->id]);
    }
}
