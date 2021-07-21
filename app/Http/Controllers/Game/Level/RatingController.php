<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\Level\Rating\SuggestionType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\ChkValidateException;
use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Rating\RateDemonRequest;
use App\Http\Requests\Game\Level\Rating\RateStarsRequest;
use App\Http\Requests\Game\Level\Rating\SuggestStarsRequest;
use App\Models\Game\Level\RatingSuggestion;
use function config;

/**
 * Class RatingController
 * @package App\Http\Controllers
 */
class RatingController extends Controller
{
    /**
     * @param SuggestStarsRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/suggestGJStars
     */
    public function suggest(SuggestStarsRequest $request, Helpers $helper): int
    {
        $data = $request->validated();

        RatingSuggestion::query()
            ->updateOrCreate([
                'type' => SuggestionType::SUGGEST,
                'user' => $request->account->user->id,
                'level' => $data['levelID']
            ], [
                'rating' => $data['stars'],
                'featured' => $data['feature']
            ]);

        if (config('game.feature.auto_rate.suggest.enabled')) {
            $query = RatingSuggestion::query()
                ->whereType(SuggestionType::SUGGEST)
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
     * @param RateStarsRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJStars211
     */
    public function rate(RateStarsRequest $request, Helpers $helper): int
    {
        try {
            $data = $request->validated();
            $request->validateChk();

            RatingSuggestion::query()
                ->firstOrCreate([
                    'type' => SuggestionType::RATE,
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

                $query = RatingSuggestion::query()
                    ->whereType(SuggestionType::RATE)
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
        } catch (ChkValidateException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }
    }

    /**
     * @param RateDemonRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJDemon21
     */
    public function demon(RateDemonRequest $request, Helpers $helper): int
    {
        $data = $request->validated();

        RatingSuggestion::query()
            ->firstOrCreate([
                'type' => SuggestionType::DEMON,
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

            $query = RatingSuggestion::query()
                ->whereType(SuggestionType::DEMON)
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
