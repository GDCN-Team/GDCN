<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Rating\RateDemonRequest;
use App\Http\Requests\Game\Level\Rating\RateStarsRequest;
use App\Http\Requests\Game\Level\Rating\SuggestStarsRequest;
use App\Services\Game\Level\RatingService;

class RatingController extends Controller
{
    /**
     * @param RatingService $services
     */
    public function __construct(
        public RatingService $services
    )
    {
    }

    /**
     * @param SuggestStarsRequest $request
     * @return int
     *
     * @throws UserNotFoundException
     * @see http://docs.gdprogra.me/#/endpoints/suggestGJStars
     */
    public function suggest(SuggestStarsRequest $request): int
    {
        $data = $request->validated();
        return $this->services->suggest($data['accountID'], $data['levelID'], $data['stars'], $data['feature'])
            ? ResponseCode::LEVEL_SUGGEST_SUCCESS : ResponseCode::LEVEL_SUGGEST_FAILED;
    }

    /**
     * @param RateStarsRequest $request
     * @return int
     *
     * @throws UserNotFoundException
     * @see http://docs.gdprogra.me/#/endpoints/rateGJStars211
     */
    public function rate(RateStarsRequest $request): int
    {
        $data = $request->validated();
        if ($this->services->suggest_rate($request->getPlayer(), $data['levelID'], $data['stars'])) {
            return ResponseCode::LEVEL_SUGGEST_RATE_SUCCESS;
        } else {
            return ResponseCode::LEVEL_SUGGEST_RATE_FAILED;
        }
    }

    /**
     * @param RateDemonRequest $request
     * @return int
     *
     * @throws UserNotFoundException
     * @see http://docs.gdprogra.me/#/endpoints/rateGJDemon21
     */
    public function demon(RateDemonRequest $request): int
    {
        $data = $request->validated();
        if ($this->services->suggest_demon($request->getPlayer(), $data['levelID'], $data['rating'])) {
            return ResponseCode::LEVEL_SUGGEST_DEMON_SUCCESS;
        } else {
            return ResponseCode::LEVEL_SUGGEST_DEMON_FAILED;
        }
    }
}
