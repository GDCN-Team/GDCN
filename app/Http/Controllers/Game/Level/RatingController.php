<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Rating\RateDemonRequest;
use App\Http\Requests\Game\Level\Rating\RateStarsRequest;
use App\Http\Requests\Game\Level\Rating\SuggestStarsRequest;
use App\Services\Game\Level\RatingService;
use Illuminate\Support\Facades\Auth;

/**
 * Class RatingController
 * @package App\Http\Controllers
 */
class RatingController extends Controller
{
    public function __construct(
        public RatingService $services
    )
    {
    }

    /**
     * @param SuggestStarsRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/suggestGJStars
     */
    public function suggest(SuggestStarsRequest $request): int
    {
        try {
            $data = $request->validated();
            return $this->services->suggest($data['accountID'], $data['levelID'], $data['stars'], $data['feature'])
                ? ResponseCode::LEVEL_SUGGEST_SUCCESS : ResponseCode::LEVEL_SUGGEST_FAILED;
        } catch (UserNotFoundException) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param RateStarsRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJStars211
     */
    public function rate(RateStarsRequest $request): int
    {
        try {
            $data = $request->validated();
            return $this->services->suggest_rate(
                $request->getPlayer(),
                $data['levelID'],
                $data['stars']
            ) ? ResponseCode::LEVEL_SUGGEST_RATE_SUCCESS : ResponseCode::LEVEL_SUGGEST_RATE_FAILED;
        } catch (UserNotFoundException) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param RateDemonRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/rateGJDemon21
     */
    public function demon(RateDemonRequest $request): int
    {
        try {
            $data = $request->validated();
            return $this->services->suggest_demon(
                $request->getPlayer(),
                $data['levelID'],
                $data['rating']
            ) ? ResponseCode::LEVEL_SUGGEST_DEMON_SUCCESS : ResponseCode::LEVEL_SUGGEST_DEMON_FAILED;
        } catch (UserNotFoundException) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }
}
