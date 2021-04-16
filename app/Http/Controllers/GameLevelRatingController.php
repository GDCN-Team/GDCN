<?php

namespace App\Http\Controllers;

use App\Enums\GameLevelRatingSuggestionType;
use App\Enums\ResponseCode;
use App\Exceptions\GameChkValidateException;
use App\Game\Helpers;
use App\Http\Requests\GameLevelRatingRateDemonRequest;
use App\Http\Requests\GameLevelRatingRateStarsRequest;
use App\Http\Requests\GameLevelRatingSuggestStarsRequest;
use App\Models\GameLevelRatingSuggestion;

/**
 * Class GameLevelRatingController
 * @package App\Http\Controllers
 */
class GameLevelRatingController extends Controller
{
    /**
     * @param GameLevelRatingSuggestStarsRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/suggestGJStars
     */
    public function suggest(GameLevelRatingSuggestStarsRequest $request, Helpers $helper): int
    {
        $data = $request->validated();

        GameLevelRatingSuggestion::query()
            ->updateOrCreate([
                'type' => GameLevelRatingSuggestionType::SUGGEST,
                'user' => $request->account->user->id,
                'level' => $data['levelID']
            ], [
                'rating' => $data['stars'],
                'featured' => $data['feature']
            ]);

        if (config('game.feature.auto_rate.suggest.enabled')) {
            $query = GameLevelRatingSuggestion::query()
                ->whereType(GameLevelRatingSuggestionType::SUGGEST)
                ->whereLevel($data['levelID']);

            if ($query->count() >= config('game.feature.auto_rate.suggest.least_suggest', 10)) {

                $isMod = ($request->account->permission->mod_level ?? 0) > 0;
                if (!$isMod) {
                    return ResponseCode::ACCOUNT_MUST_IS_NOT_MOD;
                }

                $stars = round($query->average('rating'));
                if ($stars > 10) {
                    $stars = 9;
                }

                $rating = $request->level->rate($stars);
                return $helper->bool2result($rating);
            }
        }

        return ResponseCode::OK;
    }

    /**
     * @param GameLevelRatingRateStarsRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJStars211
     */
    public function rate(GameLevelRatingRateStarsRequest $request, Helpers $helper): int
    {
        try {
            $data = $request->validated();
            $request->validateChk();

            GameLevelRatingSuggestion::query()
                ->firstOrCreate([
                    'type' => GameLevelRatingSuggestionType::RATE,
                    'user' => $request->user->id,
                    'level' => $data['levelID']
                ], [
                    'rating' => $data['stars']
                ]);

            if (config('game.feature.auto_rate.rate.enabled')) {

                $isMod = ($request->user->account->permission->mod_level ?? 0) > 0;
                if (!$isMod && config('game.feature.auto_rate.rate.mod_only')) {
                    return ResponseCode::FAILED;
                }

                $query = GameLevelRatingSuggestion::query()
                    ->whereType(GameLevelRatingSuggestionType::RATE)
                    ->whereLevel($data['levelID']);

                if ($query->count() >= config('game.feature.auto_rate.rate.least_suggest', 10)) {
                    $stars = round($query->average('rating'));

                    if (!$isMod) {
                        if ($stars >= 10) {
                            $stars = 9;
                        } elseif ($stars <= 1) {
                            $stars = 2;
                        }
                    }

                    $rating = $request->level->rate($stars);
                    $rating->stars = 0;
                    $rating->save();

                    return $helper->bool2result($rating);
                }
            }

            return ResponseCode::OK;
        } catch (GameChkValidateException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }
    }

    /**
     * @param GameLevelRatingRateDemonRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJDemon21
     */
    public function demon(GameLevelRatingRateDemonRequest $request, Helpers $helper): int
    {
        $data = $request->validated();

        GameLevelRatingSuggestion::query()
            ->firstOrCreate([
                'type' => GameLevelRatingSuggestionType::DEMON,
                'user' => $request->user->id,
                'level' => $data['levelID']
            ], [
                'rating' => $data['rating']
            ]);

        if (config('game.feature.auto_rate.demon.enabled')) {

            $isMod = ($request->user->account->permission->mod_level ?? 0) > 0;
            if (!$isMod && config('game.feature.auto_rate.demon.mod_only')) {
                return ResponseCode::ACCOUNT_MUST_IS_NOT_MOD;
            }

            $query = GameLevelRatingSuggestion::query()
                ->whereType(GameLevelRatingSuggestionType::DEMON)
                ->whereLevel($data['levelID']);

            if ($query->count() >= config('game.feature.auto_rate.demon.least_suggest', 10)) {

                if (!$request->level->rated) {
                    return ResponseCode::LEVEL_RATING_NOT_FOUND;
                }

                $rating = $request->level->rating;
                $rating->demon_difficulty = $helper->guessDemonDifficultyFromRating(round($query->average('rating')));
                $rating->save();

                return $helper->bool2result($rating);
            }
        }

        return ResponseCode::OK;
    }
}
