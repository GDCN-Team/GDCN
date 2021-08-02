<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\User\Score\GetRequest;
use App\Http\Requests\Game\User\Score\UpdateRequest;
use App\Services\Game\UserScoreService;

class UserScoresController extends Controller
{
    /**
     * @param UserScoreService $service
     */
    public function __construct(
        public UserScoreService $service
    )
    {
    }

    /**
     * @param UpdateRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJUserScore22
     */
    public function update(UpdateRequest $request): int
    {
        $data = $request->validated();
        return $this->service->update(
            $request->getPlayer(),
            $data['gameVersion'],
            $data['binaryVersion'],
            $data['stars'],
            $data['demons'],
            $data['diamonds'],
            $data['icon'],
            $data['color1'],
            $data['color2'],
            $data['iconType'],
            $data['coins'],
            $data['userCoins'],
            $data['special'],
            $data['accIcon'],
            $data['accShip'],
            $data['accBall'],
            $data['accBird'],
            $data['accDart'],
            $data['accRobot'],
            $data['accGlow'],
            $data['accSpider'],
            $data['accExplosion']
        ) ? ResponseCode::USER_SCORE_UPDATE_SUCCESS : ResponseCode::USER_SCORE_UPDATE_FAILED;
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJScores20
     */
    public function get(GetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->get($data['type'], $data['count'], $request->getPlayer());
    }
}
