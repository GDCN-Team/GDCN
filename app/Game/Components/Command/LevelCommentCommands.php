<?php

namespace App\Game\Components\Command;

use App\Exceptions\GameCommandArgumentNotFoundException;
use App\Exceptions\GameCommandAuthorizationException;
use App\Exceptions\GameCommandExecuteException;
use App\Models\GameLevelRating;
use Exception;
use Illuminate\Support\Str;
use Throwable;

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
            $this->authorize('command-rate-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        try {
            $stars = $stars ?: $this->argument('stars');
        } catch (GameCommandArgumentNotFoundException $e) {
            return $e->getMessage();
        }

        if ($stars < 1) {
            return $this->failed('Stars must > 1.');
        }

        if ($stars > 10) {
            return $this->failed('Stars must < 10.');
        }

        $this->level->rate($stars);
        return $this->success;
    }

    /**
     * @return string
     */
    protected function unrate(): string
    {
        try {
            $this->authorize('command-unrate-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            $this->level->rating->delete();
        } catch (Exception $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @param null $key
     * @param null $value
     * @return string
     */
    protected function mod($key = null, $value = null): string
    {
        try {
            $this->authorize('command-mod-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

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
                    $rating->demon_difficulty = $value;
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
        try {
            $this->authorize('command-feature-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        if ($score <= 0) {
            return $this->failed('Score must > 0');
        }

        try {
            tap($this->level->rating, function ($rating) use ($score) {
                $rating->featured_score = $score;
                $rating->save();
            });
        } catch (Exception $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @return string
     */
    protected function unfeature(): string
    {
        try {
            $this->authorize('command-unfeature-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            tap($this->level->rating, function ($rating) {
                $rating->featured_score = 0;
                $rating->save();
            });
        } catch (Exception $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @return string
     */
    protected function epic(): string
    {
        try {
            $this->authorize('command-epic-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            tap($this->level->rating, function (GameLevelRating $rating) {
                $rating->epic = true;
                $rating->saveOrFail();
            });
        } catch (Throwable $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @return string
     */
    protected function unepic(): string
    {
        try {
            $this->authorize('command-unepic-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            tap($this->level->rating, function (GameLevelRating $rating) {
                $rating->epic = false;
                $rating->saveOrFail();
            });
        } catch (Throwable $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @return string
     */
    protected function verify_coin(): string
    {
        try {
            $this->authorize('command-verify_coin-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            tap($this->level->rating, function (GameLevelRating $rating) {
                $rating->coin_verified = true;
                $rating->saveOrFail();
            });
        } catch (Throwable $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @return string
     */
    protected function unverify_coin(): string
    {
        try {
            $this->authorize('command-unverify_coin-level');
        } catch (GameCommandAuthorizationException $e) {
            return $e->getMessage();
        }

        if (!$this->level->rated) {
            return $this->failed('Level isn\'t rated.');
        }

        try {
            tap($this->level->rating, function (GameLevelRating $rating) {
                $rating->coin_verified = false;
                $rating->saveOrFail();
            });
        } catch (Throwable $e) {
            return $this->failed($e);
        }

        return $this->success;
    }

    /**
     * @param int $id
     * @return string
     */
    protected function song(int $id = 0): string
    {
        if (!$id) {
            return $this->failed('Song id must be integer.');
        }

        try {
            $this->checkOperatorIsLevelOwner();
        } catch (GameCommandExecuteException $e) {
            try {
                $this->authorize('command-song-level');
            } catch (GameCommandAuthorizationException $e) {
                return $e->getMessage();
            }
        }

        tap($this->level, function ($level) use ($id) {
            $level->song = $id;
            $level->save();
        });

        return $this->success;
    }
}
