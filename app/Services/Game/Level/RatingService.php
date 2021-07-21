<?php

namespace App\Services\Game\Level;

use App\Exceptions\Game\Level\UnRateException;
use App\Game\Helpers;
use App\Models\Game\Level;
use App\Models\Game\Level\Rating;
use App\Models\Game\UserScore;
use Exception;
use function config;

/**
 * Class RatingService
 * @package App\Services
 */
class RatingService
{
    /**
     * @var Helpers
     */
    protected $helper;

    /**
     * RatingService constructor.
     * @param Helpers $helper
     */
    public function __construct(Helpers $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Level $level
     * @param int $stars
     * @param int|null $diff
     * @return bool
     */
    public function rate(Level $level, int $stars, int $diff = null): bool
    {
        if (!$rating = $level->rating) {
            $rating = new Rating();
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
     * @param Level $level
     * @return bool|null
     * @throws UnRateException
     */
    public function un_rate(Level $level): ?bool
    {
        if (!$level->rated) {
            return false;
        }

        try {
            $result = $level->rating->delete();
            $this->recalculateCreatorPoints();
            return $result;
        } catch (Exception $e) {
            throw new UnRateException();
        }
    }

    /**
     * @param Level $level
     * @param int $score
     * @return bool
     */
    public function setFeatureScore(Level $level, int $score): bool
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
     * @param Level $level
     * @param bool $status
     * @return bool
     */
    public function setEpic(Level $level, bool $status): bool
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
     * @param Level $level
     * @param bool $sliver
     * @return bool
     */
    public function setCoinState(Level $level, bool $sliver): bool
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
        $ratings = Rating::all();
        UserScore::query()
            ->where('creator_points', '!=', 0)
            ->update(['creator_points' => 0]);

        foreach ($ratings as $rating) {
            $level = Level::find($rating->level);
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
