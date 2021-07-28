<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\Level\ScoreType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\ScoreGetRequest;
use App\Services\Game\Level\ScoreService;

/**
 * Class UserScoresController
 * @package App\Http\Controllers
 */
class ScoresController extends Controller
{
    public function __construct(
        public ScoreService $service
    )
    {
    }

    /**
     * @param ScoreGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJLevelScores211
     */
    public function get(ScoreGetRequest $request): int|string
    {
        $data = $request->validated();
        try {
            return $this->service->get($data['accountID'], $data['levelID'], ScoreType::fromValue($data['type']), $data['s1'] - 8354, $data['percent'], $data['s9'] - 5819);
        } catch (InvalidArgumentException) {
            return ResponseCode::INVALID_REQUEST;
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_FAILED;
        }
    }
}
