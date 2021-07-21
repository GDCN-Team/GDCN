<?php

namespace App\Game\Components\Command;

use App\Enums\Game\Log\Types;
use App\Exceptions\Game\Command\ArgumentNotFoundException;
use App\Exceptions\Game\Command\AuthorizationException;
use App\Exceptions\Game\Command\ExecuteException;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use App\Models\Game\Level;
use App\Models\Game\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use Throwable;

/**
 * Class Base
 * @package App\Game\Feature\Command
 */
class Base
{
    /**
     * @var string
     */
    protected $success = 'Success!';

    /**
     * @var Account
     */
    protected $operator;

    /**
     * @var Level|null
     */
    protected $level;

    /**
     * @var Comment|Comment
     */
    protected $comment;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var string[]
     */
    protected $dontExecute = [
        '__construct',
        'authorize',
        'execute',
        'failed',
        'argument',
        'checkOperatorIsLevelOwner'
    ];

    /**
     * Base constructor.
     * @param Account $operator
     * @param Comment|Comment $comment
     * @param array $arguments
     * @throws ExecuteException
     */
    public function __construct(Account $operator, $comment, array $arguments)
    {
        if (!$comment instanceof Comment && !$comment instanceof Comment) {
            throw new ExecuteException('Comment must be account comment or level comment.');
        }

        $this->operator = $operator;
        $this->comment = $comment;
        $this->arguments = $arguments;

        try {
            $this->level = $comment instanceof Comment ? Level::whereId($comment->level)->firstOrFail() : null;
        } catch (ModelNotFoundException $e) {
            throw new ExecuteException('Level not found.');
        }
    }

    /**
     * @param $flag
     * @throws AuthorizationException
     */
    public function authorize($flag): void
    {
        if (!in_array($flag, config('game.default_permissions'), true) && !optional($this->operator->permission)->can($flag)) {
            throw new AuthorizationException('Permission denied.');
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws ExecuteException
     */
    public function execute($name)
    {
        if (!method_exists($this, $name)) {
            throw new ExecuteException('Command not found.');
        }

        if (in_array($name, $this->dontExecute, true)) {
            throw new ExecuteException("Command {$name} are not allow to execute.");
        }

        if ($this->comment instanceof Comment) {
            $logType = Types::fromValue(Types::DO_ACCOUNT_COMMENT_COMMAND);

            try {
                $this->authorize("command-{$name}-account");
            } catch (AuthorizationException $e) {
                return $e->getMessage();
            }

        } elseif ($this->comment instanceof Comment) {
            $logType = Types::fromValue(Types::DO_LEVEL_COMMENT_COMMAND);

            try {
                $this->authorize("command-{$name}-level");
            } catch (AuthorizationException $e) {
                return $e->getMessage();
            }

        } else {
            throw new ExecuteException('Comment must be account comment or level comment.');
        }

        // Create log
        Log::query()
            ->insert([
                'type' => $logType,
                'value' => $name,
                'user' => $this->operator->user->id,
                'ip' => Request::ip()
            ]);

        return $this->{$name}(...$this->arguments);
    }


    /**
     * @param string|Throwable $message
     * @return string
     */
    public function failed($message): string
    {
        if ($message instanceof Throwable) {
            $message = $message->getMessage();
        }

        return "Failed: {$message}";
    }

    /**
     * @param mixed ...$names
     * @return mixed
     * @throws ArgumentNotFoundException
     */
    public function argument(...$names)
    {
        foreach ($names as $name) {
            if (!empty($this->arguments[$name])) {
                return $this->arguments[$name];
            }

            $args[] = "'{$name}'";
        }

        $arg = implode(' or ', $args ?? []);
        throw new ArgumentNotFoundException("Argument {$arg} not found.");
    }

    /**
     * @return Level|bool|null
     * @throws ExecuteException
     */
    public function checkOperatorIsLevelOwner()
    {
        if (!$this->level) {
            throw new ExecuteException('Command type must be level.');
        }

        return $this->level->creator->is($this->operator->user);
    }

    /**
     * @return string
     */
    protected function test(): string
    {
        return 'worked!';
    }
}
