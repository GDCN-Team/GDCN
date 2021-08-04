<?php

namespace App\Listeners;

use App\Events\LevelCommentUploaded;
use App\Exceptions\Game\InvalidCommandException;
use App\Services\Game\BaseCommandService;

class ExecuteLevelCommentCommand
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        public BaseCommandService $command
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LevelCommentUploaded $event
     * @return mixed
     */
    public function handle(LevelCommentUploaded $event): mixed
    {
        try {
            return $this->command->doLevelCommentCommand($event->comment);
        } catch (InvalidCommandException) {
            return 'Invalid Command!';
        }
    }
}
