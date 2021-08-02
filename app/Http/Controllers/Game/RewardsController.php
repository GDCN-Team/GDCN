<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\Game\RewardType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Reward\GetRequest;
use App\Services\Game\RewardService;
use Exception;

class RewardsController extends Controller
{
    /**
     * @param RewardService $service
     */
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
                RewardType::fromValue((int)$data['rewardType']),
                $request->getPlayer(),
                $data['gameVersion'],
                $data['binaryVersion'],
                $data['udid'],
                $data['accountID'] ?? 0,
                $data['chk']
            );
        } catch (Exception) {
            return ResponseCode::UNHANDLED_EXCEPTION;
        }
    }
}
