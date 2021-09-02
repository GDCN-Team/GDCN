<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Level\Rating\SuggestionType;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\RatingSuggestion;
use App\Services\Game\HelperService;
use Illuminate\Support\Facades\Log;

class RatingService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    public function suggest(int $accountID, int $levelID, int $stars, bool $feature): ?RatingSuggestion
    {
        $account = Account::findOrFail($accountID);
        if (!$account->user || !$account->permission_group?->can('CREATE_LEVEL_SUGGESTION')) {
            Log::channel('gdcn')
                ->notice('[Level Rating System] Action: Suggest Rating Failed', [
                    'accountID' => $accountID,
                    'levelID' => $levelID,
                    'stars' => $stars,
                    'feature' => $feature,
                    'reason' => 'No Permission',
                ]);

            return null;
        }

        Log::channel('gdcn')
            ->info('[Level Rating System] Action: Suggest Rating', [
                'accountID' => $accountID,
                'levelID' => $levelID,
                'stars' => $stars,
                'feature' => $feature
            ]);

        return Level::findOrFail($levelID)
            ->rating_suggestions()
            ->create([
                'type' => SuggestionType::SUGGEST,
                'user' => $account->user->id,
                'level' => $levelID,
                'rating' => $stars,
                'featured' => $feature
            ]);
    }

    public function suggest_rate(?string $uuid, int $levelID, int $stars): bool
    {
        $user = $this->helper->resolveUser($uuid);

        $level = Level::findOrFail($levelID);
        $suggestions = $level->rating_suggestions();

        $suggestions->firstOrCreate([
            'user' => $user->id,
        ], [
            'type' => SuggestionType::RATE,
            'level' => $levelID,
            'rating' => $stars,
            'featured' => false
        ]);

        if (config('game.feature.auto_rate.rate.enabled') && $suggestions->count() >= config('game.feature.auto_rate.rate.least_suggest')) {
            $stars = round($suggestions->average('rating'));
            $suggestions->delete();

            Log::channel('gdcn')
                ->notice('[Level Rating System] Action: Auto Rate Face', [
                    'userID' => $user->id,
                    'levelID' => $levelID,
                    'stars' => $stars
                ]);

            return $level->rating()
                ->firstOrCreate([], [
                    'stars' => 0,
                    'difficulty' => $this->guessDifficultyFromStars($stars),
                    'featured_score' => 0,
                    'epic' => false,
                    'coin_verified' => false,
                    'auto' => $stars == 0,
                    'demon' => $stars == 10,
                    'demon_difficulty' => 0
                ])->save();
        }

        Log::channel('gdcn')
            ->info('[Level Rating System] Action: Suggest Rate', [
                'userID' => $user->id,
                'levelID' => $levelID,
                'stars' => $stars
            ]);

        return true;
    }

    public function suggest_demon(?string $uuid, int $levelID, int $rating): bool
    {
        $user = $this->helper->resolveUser($uuid);

        $level = Level::findOrFail($levelID);
        $suggestions = $level->rating_suggestions();

        if (!$level->rating || $level->rating?->demon_difficulty !== '0') {
            return false;
        }

        $suggestions->firstOrCreate([
            'user' => $user->id,
        ], [
            'type' => SuggestionType::DEMON,
            'level' => $levelID,
            'rating' => $rating,
            'featured' => false
        ]);

        if (config('game.feature.auto_rate.demon.enabled') && $suggestions->count() >= config('game.feature.auto_rate.demon.least_suggest')) {
            $rating = round($suggestions->average('rating'));
            $suggestions->delete();

            Log::channel('gdcn')
                ->notice('[Level Rating System] Action: Auto Rate Demon Face', [
                    'userID' => $user->id,
                    'levelID' => $levelID,
                    'rating' => $rating
                ]);

            return $level->rating()
                ->update([
                    'demon_difficulty' => $this->guessDemonDifficultyFromRating($rating)
                ]);
        }

        Log::channel('gdcn')
            ->info('[Level Rating System] Action: Suggest Demon', [
                'userID' => $user->id,
                'levelID' => $levelID,
                'rating' => $rating
            ]);

        return true;
    }

    public function guessDifficultyFromStars(int $stars): int
    {
        return match ($stars) {
            1, 10 => 60,
            2 => 10,
            3 => 20,
            4, 5 => 30,
            6, 7 => 40,
            8, 9 => 50,
            default => 0
        };
    }

    public function guessDemonDifficultyFromRating(?int $rating): int
    {
        return match ($rating) {
            1 => 3,
            2 => 4,
            4 => 5,
            5 => 6,
            default => 0,
        };
    }
}
