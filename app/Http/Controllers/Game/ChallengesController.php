<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\ChallengeGenerateException;
use App\Exceptions\Game\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Challenge\GetRequest;
use App\Services\Game\ChallengeService;

/**
 * Class ChallengesController
 * @package App\Http\Controllers
 */
class ChallengesController extends Controller
{
    public function __construct(
        public ChallengeService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJChallenges
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->get($data['udid'], $data['accountID'] ?? 0, $data['chk']);
        } catch (InvalidArgumentException) {
            return ResponseCode::INVALID_REQUEST;
        } catch (ChallengeGenerateException) {
            return ResponseCode::CHALLENGE_GET_FAILED;
        }
    }
}
