<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\Game\RewardType;
use App\Exceptions\Game\UserScoreNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Reward\GetRequest;
use App\Services\Game\RewardService;
use Exception;

/**
 * Class RewardsController
 * @package App\Http\Controllers
 */
class RewardsController extends Controller
{
    public function __construct(
        public RewardService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJRewards
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->get(
                RewardType::fromValue($data['rewardType']),
                $request->getPlayer(),
                $data['gameVersion'],
                $data['binaryVersion'],
                $data['udid'],
                $data['accountID'] ?? 0,
                $data['chk']
            );
        } catch (UserScoreNotFoundException) {
            return ResponseCode::USER_SCORE_NOT_FOUND;
        } catch (Exception) {
            return ResponseCode::UNHANDLED_EXCEPTION;
        }
    }
}
