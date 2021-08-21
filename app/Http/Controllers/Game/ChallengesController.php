<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Challenge\GetRequest;
use App\Services\Game\ChallengeService;
use Exception;
use Illuminate\Support\Arr;

class ChallengesController extends Controller
{
    public function __construct(
        public ChallengeService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJChallenges
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->get(
                Arr::getAny($data, ['accountID', 'udid']),
                $data['udid'],
                $data['accountID'] ?? 0,
                $data['chk']
            );
        } catch (Exception) {
            return ResponseCode::UNHANDLED_EXCEPTION;
        }
    }
}
