<?php

namespace App\Services\Game\Level;

use App\Exceptions\Game\InvalidCommandException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;

class CommentCommandService
{
    protected array $executable = [
        'test'
    ];

    public function __construct(
        protected Account      $operator,
        protected Level        $level,
        protected LevelComment $comment,
        protected string       $command,
        protected array        $arguments,
        protected array        $options
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
