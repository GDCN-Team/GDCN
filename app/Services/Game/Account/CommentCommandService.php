<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\CommandNotFoundException;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use Illuminate\Support\Facades\App;

class CommentCommandService
{
    public function __construct(
        protected Account        $operator,
        protected AccountComment $comment,
        protected string         $command,
        protected array          $arguments,
        protected array          $options
    )
    {
    }

    /**
     * @throws CommandNotFoundException
     */
    public function execute(): mixed
    {
        if (!in_array($this->command, ['__construct', 'execute']) && method_exists($this, $this->command)) {
            $this->comment->delete();
            return App::call([$this, $this->command]);
        }

        throw new CommandNotFoundException();
    }

    public function test(): string
    {
        return 'worked!';
    }
}
