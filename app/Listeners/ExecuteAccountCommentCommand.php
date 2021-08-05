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
     * @return mixed
     */
    protected function shouldExecute(): mixed
    {
        return config('game.feature.command.account_comment');
    }

    /**
     * Handle the event.
     *
     * @param AccountCommentUploaded $event
     * @return mixed
     */
    public function handle(AccountCommentUploaded $event): mixed
    {
        if ($this->command->isCommand($event->comment) && $this->shouldExecute()) {
            try {
                return $this->command->doAccountCommentCommand($event->comment);
            } catch (InvalidCommandException) {
                return 'Invalid Command!';
            }
        }

        return null;
    }
}
