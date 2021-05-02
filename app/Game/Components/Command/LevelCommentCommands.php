<?php

namespace App\Game\Components\Command;

use App\Exceptions\GameCommandArgumentNotFoundException;
use App\Exceptions\GameCommandExecuteException;
use App\Game\Helpers;
use App\Models\GameDailyLevel;
use App\Models\GameWeeklyLevel;
use App\Services\GameLevelRatingService;
use App\Services\GameLevelService;
use Illuminate\Support\Str;

/**
 * Class LevelCommentCommands
 * @package App\Game\Feature\Command
 */
class LevelCommentCommands extends Base
{
    /**
     * @param $stars
     * @return string
     */
    protected function rate($stars = 0): string
    {
        try {
            $stars = $stars ?: $this->argument('stars');
        } catch (GameCommandArgumentNotFoundException $e) {
            return $e->getMessage();
        }

        if ($stars < 1 || $stars > 10) {
            return $this->failed('Stars must between 1 to 10.');
        }

        return app(GameLevelRatingService::class)->rate($this->level, $stars) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function unrate(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        $result = app(GameLevelRatingService::class)->un_rate($this->level);
        return $result === true ? $this->success : $this->failed($result);
    }

    /**
     * @param null $key
     * @param null $value
     * @return string
     */
    protected function mod($key = null, $value = null): string
    {
        try {
            $key = $key ?: $this->argument('key');
        } catch (GameCommandArgumentNotFoundException $e) {
            return $e->getMessage();
        }

        try {
            $value = $value ?: $this->argument('value', 'val');
        } catch (GameCommandArgumentNotFoundException $e) {
            return $e->getMessage();
        }

        switch (Str::camel($key)) {
            case 'demonDiff':
            case 'demonDifficulty':
                if ($value < 1 || $value > 5) {
                    return $this->failed('Value must between 1 and 5.');
                }

                if (!$this->level->rated) {
                    return $this->failed('Level isn\'t rated.');
                }

                tap($this->level->rating, function ($rating) use ($value) {
                    $demon_difficulty = app(Helpers::class)->guessDemonDifficultyFromRating($value);
                    $rating->demon_difficulty = $demon_difficulty;
                    $rating->save();
                });
                break;
            default:
                return $this->failed('Invalid key.');
        }

        return $this->success;
    }

    /**
     * @param int $score
     * @return string
     */
    protected function feature($score = 1): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        if ($score <= 0) {
            return $this->failed('Score must > 0');
        }

        return app(GameLevelRatingService::class)->setFeatureScore($this->level, $score) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function unfeature(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        return app(GameLevelRatingService::class)->setFeatureScore($this->level, 0) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function epic(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        return app(GameLevelRatingService::class)->setEpic($this->level, true) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function unepic(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        return app(GameLevelRatingService::class)->setEpic($this->level, false) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function verify_coin(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        return app(GameLevelRatingService::class)->setCoinState($this->level, true) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    protected function unverify_coin(): string
    {
        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        return app(GameLevelRatingService::class)->setCoinState($this->level, false) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @param int $songID
     * @return string
     */
    protected function song(int $songID = 0): string
    {
        try {
            $this->checkOperatorIsLevelOwner();
        } catch (GameCommandExecuteException $e) {
            return $e->getMessage();
        }

        return app(GameLevelService::class)->setSong($this->level, $songID) ? $this->success : $this->failed('Unknown Error');
    }

    /**
     * @return string
     */
    public function daily(): string
    {
        $lastDailyTime = GameDailyLevel::query()
            ->latest()
            ->value('time');

        $daily = new GameDailyLevel;
        $daily->level = $this->level->id;
        $daily->time = !empty($lastDailyTime) ? ($lastDailyTime + 86400) : strtotime('tomorrow 00:00:00');
        $daily->save();

        return $this->success;
    }

    /**
     * @return string
     */
    public function weekly(): string
    {
        $lastWeeklyTime = GameWeeklyLevel::query()
            ->latest()
            ->value('time');

        $weekly = new GameWeeklyLevel;
        $weekly->level = $this->level->id;
        $weekly->time = !empty($lastWeeklyTime) ? ($lastWeeklyTime + 604800) : strtotime('next monday 00:00:00');
        $weekly->save();

        return $this->success;
    }
}
