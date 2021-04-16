<?php

namespace App\Services;

use App\Game\Helpers;
use App\Models\GameLevel;
use App\Models\GameLevelRating;
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
        $rating = $level->rating;
        if (!$rating) {
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
        return $rating->save();
    }

    /**
     * @param GameLevel $level
     * @return bool|string|null
     */
    public function unrate(GameLevel $level)
    {
        if (!$level->rated) {
            return false;
        }

        try {
            return $level->rating->delete();
        } catch (Exception $e) {
            return $e->getMessage();
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
        return $level->rating->save();
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
        return $level->rating->save();
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
        foreach ($ratings as $rating) {
            if (!$score = GameLevel::find($rating->level)->creator->score) {
                continue;
            }

            $score->creator_points = 0;
            if ($rating->stars > 0) {
                ++$score->creator_points;
            }

            if ($rating->featured_score > 0) {
                $score->creator_points += 2;
            }

            if ($rating->epic) {
                $score->creator_points += 3;
            }

            $score->save();
        }

        return true;
    }
}
