<?php

namespace App\Services\Game;

use App\Exceptions\Game\CommandNotFoundException;
use App\Exceptions\Game\InvalidCommandException;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level\Comment as LevelComment;
use App\Services\Game\Account\CommentCommandService as AccountCommentCommandService;
use App\Services\Game\Level\CommentCommandService as LevelCommentCommandService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommandService
{
    protected string $prefix = '!';
    protected string $option_prefix = '-';
    protected string $argument_prefix = '--';
    protected string $argument_split = '=';

    /**
     * @throws InvalidCommandException
     * @throws CommandNotFoundException
     */
    public function execute(AccountComment|LevelComment $comment)
    {
        switch ($comment::class) {
            case AccountComment::class:
                if (!config('game.feature.command.account_comment.enabled')) {
                    throw new InvalidCommandException();
                }

                $service = AccountCommentCommandService::class;
                break;
            case LevelComment::class:
                if (!config('game.feature.command.level_comment.enabled')) {
                    throw new InvalidCommandException();
                }

                $service = LevelCommentCommandService::class;
                break;
            default:
                throw new InvalidCommandException();
        }

        /** @var Account $account */
        $account = $comment->getRelationValue('account');

        [$name, $arguments, $options] = $this->parseCommand($comment->content);
        if ($service === LevelCommentCommandService::class) {
            $level = $comment->getRelationValue('level');
            $command = new $service($account, $comment, $level, $name, $arguments, $options);
        } else {
            $command = new $service($account, $comment, $name, $arguments, $options);
        }

        /** @var AccountCommentCommandService|LevelCommentCommandService $command */
        $commandExecuteResult = $command->execute();

        Log::notice('[Comment Command System] Command Executed', [
            'service' => $service,
            'operatorAccountID' => $account->id,
            'levelID' => $comment->level,
            'content' => $comment->content,
            'name' => $name,
            'arguments' => $arguments,
            'options' => $options,
            'result' => $commandExecuteResult
        ]);

        return $commandExecuteResult;
    }

    /**
     * @throws InvalidCommandException
     */
    protected function parseCommand(string $command): array
    {
        $params = explode(' ', $command);
        if (!Str::startsWith($command, $this->prefix)) {
            throw new InvalidCommandException();
        }

        $command_name = Str::substr($params[0], 1);
        $arguments = [];
        $options = [];

        foreach ($params as $param) {
            if (Str::startsWith($param, $this->argument_prefix)) {
                [$key, $value] = explode($this->argument_split, $param, 2);
                $arguments[Str::substr($key, strlen($this->argument_prefix))] = $value;
            } elseif (Str::startsWith($param, $this->option_prefix)) {
                $options[] = Str::substr($param, strlen($this->option_prefix));
            } else {
                $arguments[] = $param;
            }
        }

        return [$command_name, $arguments, $options];
    }
}
