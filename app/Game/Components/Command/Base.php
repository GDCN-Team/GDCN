<?php

namespace App\Game\Components\Command;

use App\Enums\GameLogType;
use App\Exceptions\GameCommandArgumentNotFoundException;
use App\Exceptions\GameCommandAuthorizationException;
use App\Exceptions\GameCommandExecuteException;
use App\Models\GameAccount;
use App\Models\GameAccountComment;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use App\Models\GameLog;
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
     * @var GameAccount
     */
    protected $operator;

    /**
     * @var GameLevel|null
     */
    protected $level;

    /**
     * @var GameAccountComment|GameLevelComment
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
     * @param GameAccount $operator
     * @param GameAccountComment|GameLevelComment $comment
     * @param array $arguments
     * @throws GameCommandExecuteException
     */
    public function __construct(GameAccount $operator, $comment, array $arguments)
    {
        if (!$comment instanceof GameAccountComment && !$comment instanceof GameLevelComment) {
            throw new GameCommandExecuteException('Comment must be account comment or level comment.');
        }

        $this->operator = $operator;
        $this->comment = $comment;
        $this->arguments = $arguments;

        try {
            $this->level = $comment instanceof GameLevelComment ? GameLevel::whereId($comment->level)->firstOrFail() : null;
        } catch (ModelNotFoundException $e) {
            throw new GameCommandExecuteException('Level not found.');
        }
    }

    /**
     * @param $flag
     * @throws GameCommandAuthorizationException
     */
    public function authorize($flag): void
    {
        if (!in_array($flag, config('game.default_permissions'), true) && !optional($this->operator->permission)->can($flag)) {
            throw new GameCommandAuthorizationException("Permission denied.");
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws GameCommandExecuteException
     */
    public function execute($name)
    {
        if (!method_exists($this, $name)) {
            throw new GameCommandExecuteException('Command not found.');
        }

        if (in_array($name, $this->dontExecute, true)) {
            throw new GameCommandExecuteException("Command {$name} are not allow to execute.");
        }

        if ($this->comment instanceof GameAccountComment) {
            $logType = GameLogType::fromValue(GameLogType::DO_ACCOUNT_COMMENT_COMMAND);

            try {
                $this->authorize("command-{$name}-account");
            } catch (GameCommandAuthorizationException $e) {
                return $e->getMessage();
            }

        } elseif ($this->comment instanceof GameLevelComment) {
            $logType = GameLogType::fromValue(GameLogType::DO_LEVEL_COMMENT_COMMAND);

            try {
                $this->authorize("command-{$name}-level");
            } catch (GameCommandAuthorizationException $e) {
                return $e->getMessage();
            }

        } else {
            throw new GameCommandExecuteException('Comment must be account comment or level comment.');
        }

        // Create log
        GameLog::query()
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
     * @throws GameCommandArgumentNotFoundException
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
        throw new GameCommandArgumentNotFoundException("Argument {$arg} not found.");
    }

    /**
     * @return GameLevel|bool|null
     * @throws GameCommandExecuteException
     */
    public function checkOperatorIsLevelOwner()
    {
        if (!$this->level) {
            throw new GameCommandExecuteException('Command type must be level.');
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
