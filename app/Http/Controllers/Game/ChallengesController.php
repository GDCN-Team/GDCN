<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\ChallengeGenerateException;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Challenge\GetRequest;
use App\Services\Game\ChallengeService;

class ChallengesController extends Controller
{
    /**
     * @param ChallengeService $service
     */
    public function __construct(
        public ChallengeService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @throws ChallengeGenerateException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * @see http://docs.gdprogra.me/#/endpoints/getGJChallenges
     */
    public function get(GetRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->get(
            $request->getPlayer(),
            $data['udid'],
            $data['accountID'] ?? 0,
            $data['chk']
        );
    }
}
