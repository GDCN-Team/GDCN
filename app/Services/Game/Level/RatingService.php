<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Level\Rating\SuggestionType;
use App\Exceptions\Game\Level\UnRateException;
use App\Exceptions\Game\UserNotFoundException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Rating;
use App\Models\Game\Level\RatingSuggestion;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use App\Services\Game\HelperService;
use BenSampo\Enum\Exceptions\InvalidEnumKeyException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function config;

/**
 * Class RatingService
 * @package App\Services
 */
class RatingService
{
    /**
     * RatingService constructor.
     * @param HelperService $helper
     */
    public function __construct(
        public HelperService $helper
    )
    {

    }

    /**
     * @param SuggestionType $type
     * @param $suggestion
     * @param bool $clearStars
     * @return bool
     */
    public function auto_rate(SuggestionType $type, $suggestion, bool $clearStars = true): bool
    {
        switch ($type->value) {
            case SuggestionType::SUGGEST:
                $config = config('game.feature.auto_rate.suggest');
                break;
            case SuggestionType::RATE:
                $config = config('game.feature.auto_rate.rate');
                break;
            case SuggestionType::DEMON:
                $config = config('game.feature.auto_rate.demon');
                break;
            default:
                return false;
        }

        $suggestions = RatingSuggestion::where([
            'type' => $type->value,
            'level' => $suggestion->level
        ]);

        $level = $this->helper->getModel($suggestion->level, Level::class);
        if ($config['enabled'] && $suggestions->count() >= $config['least_suggest']) {
            $stars = round($suggestions->average('rating'));

            if ($type->value !== SuggestionType::DEMON) {
                $rating = $this->rate($level, $stars);

                if ($type->value === SuggestionType::SUGGEST) {
                    $featured = max(0, round($suggestions->average('featured')));
                    $rating->featured_score = $featured;
                    $rating->save();
                }
            } else {
                $rating = $level->rating;
                $rating->demon_difficulty = $this->helper->guessDemonDifficultyFromRating(round($suggestions->average('rating')));
                $rating->save();
            }

            if ($clearStars) {
                $rating->stars = 0;
                $rating->save();
            }
        }

        return true;
    }

    /**
     * @param SuggestionType $type
     * @param $user
     * @param $level
     * @param int $rating
     * @param bool $featured
     * @return Model|Builder|RatingSuggestion
     */
    public function upload_suggestion(SuggestionType $type, $user, $level, int $rating, bool $featured = false): Model|Builder|RatingSuggestion
    {
        return RatingSuggestion::query()
            ->updateOrCreate([
                'type' => $type->value,
                'user' => $this->helper->getID($user),
                'level' => $this->helper->getID($level)
            ], [
                'rating' => $rating,
                'featured' => $featured
            ]);
    }

    /**
     * @param $account
     * @param $level
     * @param int $stars
     * @param bool $featured
     * @return bool
     * @throws UserNotFoundException
     */
    public function suggest($account, $level, int $stars, bool $featured): bool
    {
        $account = $this->helper->getModel($account, Account::class);
        if (!$user = $account->user) {
            throw new UserNotFoundException();
        }

        try {
            $type = SuggestionType::fromKey('SUGGEST');
            $suggestion = $this->upload_suggestion($type, $user, $level, $stars, $featured);
        } catch (InvalidEnumKeyException) {
            return false;
        }

        return $this->auto_rate($type, $suggestion, false);
    }

    /**
     * @param $user
     * @param $level
     * @param int $stars
     * @return bool
     * @throws UserNotFoundException
     */
    public function suggest_rate($user, $level, int $stars): bool
    {
        if (!$user = $this->helper->getModel($user, User::class)) {
            throw new UserNotFoundException();
        }

        try {
            $type = SuggestionType::fromKey('RATE');
            $suggestion = $this->upload_suggestion($type, $user, $level, $stars);
        } catch (InvalidEnumKeyException) {
            return false;
        }

        return $this->auto_rate($type, $suggestion);
    }

    /**
     * @param $user
     * @param $level
     * @param int $rating
     * @return bool
     * @throws UserNotFoundException
     */
    public function suggest_demon($user, $level, int $rating): bool
    {
        if (!$user = $this->helper->getModel($user, User::class)) {
            throw new UserNotFoundException();
        }

        try {
            $type = SuggestionType::fromKey('DEMON');
            $suggestion = $this->upload_suggestion($type, $user, $level, $rating);
        } catch (InvalidEnumKeyException) {
            return false;
        }

        return $this->auto_rate($type, $suggestion);
    }

    /**
     * @param int|Level $level
     * @param int $stars
     * @param int|null $diff
     * @return Rating
     */
    public function rate(Level|int $level, int $stars, int $diff = null): Rating
    {
        $level = $this->helper->getModel($level, Level::class);

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
        $rating->save();

        $this->recalculateCreatorPoints();
        return $rating;
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
        } catch (Exception) {
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
