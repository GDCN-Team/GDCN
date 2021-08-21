<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\Level\ScoreType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\ScoreGetRequest;
use App\Services\Game\Level\ScoreService;

class ScoresController extends Controller
{
    public function __construct(
        public ScoreService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJLevelScores211
     * @throws InvalidArgumentException
     */
    public function get(ScoreGetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->get(
                $data['accountID'],
                $data['levelID'],
                ScoreType::fromValue($data['type']),
                $data['s1'] - 8354,
                $data['percent'],
                $data['s9'] - 5819
            );
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_FAILED;
        }
    }
}
