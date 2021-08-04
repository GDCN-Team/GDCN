<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\InvalidCommandException;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;

class CommentCommandService
{
    protected array $executable = [
        'test'
    ];

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
     * @return mixed
     * @throws InvalidCommandException
     */
    public function execute(): mixed
    {
        if (in_array($this->command, $this->executable) && method_exists($this, $this->command)) {
            $this->comment->delete();
            return call_user_func([$this, $this->command]);
        }

        throw new InvalidCommandException();
    }

    /**
     * @return string
     */
    public function test(): string
    {
        return 'worked!';
    }
}
