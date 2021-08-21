<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Rating\RateDemonRequest;
use App\Http\Requests\Game\Level\Rating\RateStarsRequest;
use App\Http\Requests\Game\Level\Rating\SuggestStarsRequest;
use App\Services\Game\Level\RatingService;
use Illuminate\Support\Arr;

class RatingController extends Controller
{
    public function __construct(
        public RatingService $services
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/suggestGJStars
     */
    public function suggest(SuggestStarsRequest $request): int
    {
        $data = $request->validated();
        if (!$this->services->suggest($data['accountID'], $data['levelID'], $data['stars'], $data['feature'])) {
            return ResponseCode::LEVEL_SUGGEST_FAILED;
        }

        return ResponseCode::LEVEL_SUGGEST_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/rateGJStars211
     */
    public function rate(RateStarsRequest $request): int
    {
        $data = $request->validated();
        if (!$this->services->suggest_rate(Arr::getAny($data, ['accountID', 'udid']), $data['levelID'], $data['stars'])) {
            return ResponseCode::LEVEL_SUGGEST_RATE_FAILED;
        }

        return ResponseCode::LEVEL_SUGGEST_RATE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/rateGJDemon21
     */
    public function demon(RateDemonRequest $request): int
    {
        $data = $request->validated();
        if (!$this->services->suggest_demon(Arr::getAny($data, ['accountID', 'udid']), $data['levelID'], $data['rating'])) {
            return ResponseCode::LEVEL_SUGGEST_DEMON_FAILED;
        }

        return ResponseCode::LEVEL_SUGGEST_DEMON_SUCCESS;
    }
}
