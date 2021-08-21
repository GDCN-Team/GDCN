<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\User\Score\GetRequest;
use App\Http\Requests\Game\User\Score\UpdateRequest;
use App\Services\Game\UserScoreService;
use Illuminate\Support\Arr;

class UserScoresController extends Controller
{
    public function __construct(
        public UserScoreService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/updateGJUserScore22
     */
    public function update(UpdateRequest $request): int
    {
        $data = $request->validated();
        if (!$user = $this->service->update($data['userName'], Arr::getAny($data, ['accountID', 'udid']), $data['udid'] ?? null, $data['gameVersion'], $data['binaryVersion'], $data['stars'], $data['demons'], $data['diamonds'], $data['icon'], $data['color1'], $data['color2'], $data['iconType'], $data['coins'], $data['userCoins'], $data['special'], $data['accIcon'], $data['accShip'], $data['accBall'], $data['accBird'], $data['accDart'], $data['accRobot'], $data['accGlow'], $data['accSpider'], $data['accExplosion'])) {
            return ResponseCode::USER_SCORE_UPDATE_FAILED;
        }

        return $user->id;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJScores20
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->get(Arr::getAny($data, ['accountID', 'udid']), $data['type'], $data['count']);
    }
}
