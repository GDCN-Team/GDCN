<?php

namespace App\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\GameCommandExecuteException;
use App\Game\Components\Command\AccountCommentCommands;
use App\Game\Components\Command\LevelCommentCommands;
use App\Models\GameAccountComment;
use App\Models\GameLevelComment;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use InvalidArgumentException;


class Helpers
{
    /**
     * @var int
     */
    public $perPage;

    /**
     * GameHelperController constructor.
     */
    public function __construct()
    {
        $this->perPage = Config::get('game.perPage', 10);
    }

    /**
     * @return string[]
     */
    public function getDifficulties(): array
    {
        return [
            10 => 'Easy',
            20 => 'Normal',
            30 => 'Hard',
            40 => 'Harder',
            50 => 'Insane'
        ];
    }

    /**
     * @param int $total
     * @param int|null $page
     * @param int|null $perPage
     * @return string
     */
    public function generatePageHash(int $total, int $page, int $perPage = null): string
    {
        if (!$perPage) {
            $perPage = $this->perPage;
        }

        $offset = max(0, $perPage * --$page);
        return "{$total}:{$offset}:{$perPage}";
    }

    /**
     * @param $comment
     * @param bool $urlBase64Decode
     * @return bool
     */
    public function checkSpam($comment, bool $urlBase64Decode = true): bool
    {
        $comment = $urlBase64Decode ? Base64Url::decode($comment) : $comment;
        $spamWords = config('game.spamWords', []);

        foreach ($spamWords as $spamWord) {
            $has = stripos($comment, $spamWord);
            if ($has === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $gjp
     * @return string
     */
    public function decodeGJP($gjp): string
    {
        $gjp = Base64Url::decode($gjp);
        return XORCipher::cipher($gjp, 37526);
    }

    /**
     * @param $stars
     * @return array
     */
    public function guessDiffFromStars($stars): array
    {
        $auto = false;
        $demon = false;

        switch ($stars) {
            case 1:
                $name = "Auto";
                $diff = 50;
                $auto = true;
                break;
            case 2:
                $name = "Easy";
                $diff = 10;
                break;
            case 3:
                $name = "Normal";
                $diff = 20;
                break;
            case 4:
            case 5:
                $name = "Hard";
                $diff = 30;
                break;
            case 6:
            case 7:
                $name = "Harder";
                $diff = 40;
                break;
            case 8:
            case 9:
                $name = "Insane";
                $diff = 50;
                break;
            case 10:
                $name = "Demon";
                $diff = 50;
                $demon = true;
                break;
            default:
                $name = $stars > 10 ? "Demon" : "N/A";
                $diff = $stars > 10 ? 50 : 0;
                $demon = $stars > 10;
                break;
        }

        return [$name, $diff, $auto, $demon];
    }

    /**
     * @param array $values
     * @param array $array
     * @param $default
     * @return mixed
     */
    public function findFromArray(array $values, array $array, $default = null)
    {
        foreach ($values as $value) {
            if (!isset($array[$value])) {
                continue;
            }

            if (!empty($array[$value])) {
                return $array[$value];
            }
        }

        return $default;
    }

    /**
     * @param GameAccountComment|GameLevelComment $comment
     * @return string|null
     */
    public function doCommand($comment): ?string
    {
        if ($comment instanceof GameAccountComment && config('game.feature.command.account_comment.enabled')) {
            $class = AccountCommentCommands::class;
            $prefix = config('game.feature.command.account_comment.prefix', '!');
        } else if ($comment instanceof GameLevelComment && config('game.feature.command.level_comment.enabled')) {
            $class = LevelCommentCommands::class;
            $prefix = config('game.feature.command.level_comment.prefix', '!');
        } else {
            return null;
        }

        try {
            $content = Base64Url::decode($comment->content);
        } catch (InvalidArgumentException $e) {
            $content = $comment->content;
        }

        if (Str::startsWith($content, $prefix)) {
            [$name, $arguments] = $this->parseCommand($content);
            $name = Str::snake(substr($name, strlen($prefix)));

            try {
                $command = new $class($comment->sender, $comment, $arguments);
                $result = $command->execute($name);
            } catch (GameCommandExecuteException $e) {
                return null;
            }

            if (!empty($result)) {
                $comment->delete();
                return "temp_0_$result";
            }

            return null;
        }

        return null;
    }

    /**
     * @param $command
     * @return array
     */
    public function parseCommand($command): array
    {
        $arguments = preg_split('/\s/', $command);
        $commandName = $arguments[0];
        unset($arguments[0]);

        foreach ($arguments as $index => $argument) {
            if (Str::startsWith($argument, '-')) {
                $array = explode('=', $argument);

                $name = strtr($array[0], ['-' => null]);
                $value = $array[1] ?? true;
                $arguments[$name] = $value;

                unset($arguments[$index]);
            }
        }

        return [$commandName, $arguments];
    }

    /**
     * @param mixed $flag
     * @return int
     */
    public function bool2result($flag): int
    {
        return (bool)$flag ? ResponseCode::OK : ResponseCode::FAILED;
    }

    /**
     * @param $rating
     * @return int|null
     */
    public function guessDemonDifficultyFromRating($rating): ?int
    {
        switch ($rating) {
            case 1:
                return 3;
            case 2:
                return 4;
            case 4:
                return 5;
            case 5:
                return 6;
            case 3:
            default:
                return 0;
        }
    }
}
