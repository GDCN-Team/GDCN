<?php

namespace App\Services;

use App\Exceptions\Game\LevelUnRateException;
use App\Game\Helpers;
use App\Models\GameLevel;
use App\Models\GameLevelRating;
use App\Models\GameUserScore;
use Exception;

/**
 * Class GameLevelRatingService
 * @package App\Services
 */
class GameLevelRatingService
{
    /**
     * @var Helpers
     */
    protected $helper;

    /**
     * GameLevelRatingService constructor.
     * @param Helpers $helper
     */
    public function __construct(Helpers $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param GameLevel $level
     * @param int $stars
     * @param int|null $diff
     * @return bool
     */
    public function rate(GameLevel $level, int $stars, int $diff = null): bool
    {
        if (!$rating = $level->rating) {
            $rating = new GameLevelRating;
            $rating->level = $level->id;
        }

        $info = $this->helper->guessDiffFromStars($stars);
        $diff = $diff ?? $info[1];

        $rating->difficulty = $diff ?: $this->helper->guessDiffFromStars($stars);
        $rating->stars = $stars;
        $rating->auto = $info[2];
        $rating->demon = $info[3];
        $rating->featured_score = 0;
        $rating->epic = false;
        $rating->coin_verified = false;
        $rating->demon_difficulty = 0;

        $result = $rating->save();
        $this->recalculateCreatorPoints();
        return $result;
    }

    /**
     * @param GameLevel $level
     * @return bool|null
     * @throws LevelUnRateException
     */
    public function un_rate(GameLevel $level): ?bool
    {
        if (!$level->rated) {
            return false;
        }

        try {
            $result = $level->rating->delete();
            $this->recalculateCreatorPoints();
            return $result;
        } catch (Exception $e) {
            throw new LevelUnRateException;
        }
    }

    /**
     * @param GameLevel $level
     * @param int $score
     * @return bool
     */
    public function setFeatureScore(GameLevel $level, int $score): bool
    {
        if (!$level->rated) {
            return false;
        }

        $level->rating->featured_score = $score;
        $result = $level->rating->save();
        $this->recalculateCreatorPoints();
        return $result;
    }

    /**
     * @param GameLevel $level
     * @param bool $status
     * @return bool
     */
    public function setEpic(GameLevel $level, bool $status): bool
    {
        if (!$level->rated) {
            return false;
        }

        $level->rating->epic = $status;
        $result = $level->rating->save();
        $this->recalculateCreatorPoints();
        return $result;
    }

    /**
     * @param GameLevel $level
     * @param bool $sliver
     * @return bool
     */
    public function setCoinState(GameLevel $level, bool $sliver): bool
    {
        if (!$level->rated) {
            return false;
        }

        $level->rating->coin_verified = $sliver;
        return $level->rating->save();
    }

    /**
     * @return bool
     */
    public function recalculateCreatorPoints(): bool
    {
        $ratings = GameLevelRating::all();
        GameUserScore::query()
            ->where('creator_points', '!=', 0)
            ->update(['creator_points' => 0]);

        foreach ($ratings as $rating) {
            $level = GameLevel::find($rating->level);
            if (!$level || !$score = $level->creator->score) {
                continue;
            }

            $creator_points = 0;
            if ($rating->stars > 0) {
                $creator_points += config('game.creator_points_count.rated', 1);
            }

            if ($rating->featured_score > 0) {
                $creator_points += config('game.creator_points_count.featured', 2);
            }

            if ($rating->epic) {
                $creator_points += config('game.creator_points_count.epic', 4);
            }

            $score->creator_points = $creator_points;
            $score->save();
        }

        return true;
    }
}
