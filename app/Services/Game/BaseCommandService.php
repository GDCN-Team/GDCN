<?php

namespace App\Services\Game;

use App\Enums\Game\Log\Types;
use App\Exceptions\Game\InvalidCommandException;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Log;
use App\Services\Game\Account\CommentCommandService as AccountCommentCommandService;
use App\Services\Game\Level\CommentCommandService;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class BaseCommandService
{
    protected string $prefix = '!';
    protected string $option_prefix = '-';
    protected string $argument_prefix = '--';
    protected string $argument_split = '=';

    /**
     * @param AccountComment $comment
     * @return mixed
     * @throws InvalidCommandException
     */
    public function doAccountCommentCommand(AccountComment $comment): mixed
    {
        if (!$this->isCommand($comment)) {
            throw new InvalidCommandException();
        }

        $command = $this->decodeComment($comment);
        Log::create([
            'type' => Types::DO_ACCOUNT_COMMENT_COMMAND,
            'user' => $comment->sender->user->id,
            'value' => $command,
            'ip' => Request::ip()
        ]);

        $service = new AccountCommentCommandService(
            $comment->sender,
            $comment,
            ...$this->parseCommand($command)
        );

        return $service->execute();
    }

    /**
     * @param AccountComment|LevelComment $comment
     * @param bool $decode
     * @return bool
     */
    public function isCommand(AccountComment|LevelComment $comment, bool $decode = true): bool
    {
        return Str::startsWith(
            $this->decodeComment($comment, $decode),
            $this->prefix
        );
    }

    /**
     * @param AccountComment|LevelComment $comment
     * @param bool $decode
     * @return string
     */
    protected function decodeComment(AccountComment|LevelComment $comment, bool $decode = true): string
    {
        return $decode === true ? Base64Url::decode($comment->content) : $comment->content;
    }

    /**
     * @param string $command
     * @return array
     * @throws InvalidCommandException
     */
    protected function parseCommand(string $command): array
    {
        $params = explode(' ', $command);
        if (!Str::startsWith($params[0], $this->prefix)) {
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

    /**
     * @param LevelComment $comment
     * @return mixed
     * @throws InvalidCommandException
     */
    public function doLevelCommentCommand(LevelComment $comment): mixed
    {
        if (!$this->isCommand($comment)) {
            throw new InvalidCommandException();
        }

        $command = $this->decodeComment($comment);
        Log::create([
            'type' => Types::DO_LEVEL_COMMENT_COMMAND,
            'user' => $comment->sender->user->id,
            'value' => $command,
            'ip' => Request::ip()
        ]);

        $service = new CommentCommandService(
            $comment->sender,
            $comment->getRelationValue('level'),
            $comment,
            ...$this->parseCommand($command)
        );

        return $service->execute();
    }
}
