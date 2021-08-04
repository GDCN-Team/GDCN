<?php

namespace App\Listeners;

use App\Events\AccountCommentUploaded;
use App\Exceptions\Game\InvalidCommandException;
use App\Services\Game\BaseCommandService;

class ExecuteAccountCommentCommand
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
     * @param AccountCommentUploaded $event
     * @return mixed
     */
    public function handle(AccountCommentUploaded $event): mixed
    {
        try {
            return $this->command->doAccountCommentCommand($event->comment);
        } catch (InvalidCommandException) {
            return 'Invalid Command!';
        }
    }
}
