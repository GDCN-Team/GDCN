<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\Game\RewardType;
use App\Exceptions\GameUserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameRewardGetRequest;
use App\Models\GameUserScore;
use Exception;
use Illuminate\Support\Str;

/**
 * Class RewardsController
 * @package App\Http\Controllers
 */
class RewardsController extends Controller
{
    /**
     * @param GameRewardGetRequest $request
     * @param HashesController $hash
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJRewards
     */
    public function get(GameRewardGetRequest $request, HashesController $hash)
    {
        try {
            $data = $request->validated();

            $user = $request->getGameUser();
            if (!$user) {
                return ResponseCode::USER_NOT_FOUND;
            }

            $userScore = $user->score;
            if (!$userScore) {
                $userScore = new GameUserScore();
                $userScore->user = $user->id;
                $userScore->game_version = $data['gameVersion'];
                $userScore->binary_version = $data['binaryVersion'];
                $userScore->save();
            }

            $chest1time = $userScore->chest1time;
            $chest1time = !empty($chest1time) ? $userScore->chest1time->getTimestamp() : 0;

            $chest1time = max(
                0,
                $chest1time + config('game.reward.small.wait', 3600) - time()
            );

            try {
                $chest1stuff = [
                    random_int(
                        config('game.reward.small.orbs.min', 200),
                        config('game.reward.small.orbs.max', 400)
                    ),
                    random_int(
                        config('game.reward.small.diamonds.min', 2),
                        config('game.reward.small.diamonds.max', 10)
                    ),
                    random_int(
                        config('game.reward.small.shards.min', 1),
                        config('game.reward.small.shards.max', 6)
                    ),
                    random_int(
                        config('game.reward.small.keys.min', 1),
                        config('game.reward.small.keys.max', 6)
                    )
                ];
            } catch (Exception $e) {
                return ResponseCode::UNHANDLED_EXCEPTION;
            }

            $chest2time = $userScore->chest2time;
            $chest2time = !empty($chest2time) ? $userScore->chest2time->getTimestamp() : 0;

            $chest2time = max(
                0,
                $chest2time + config('game.reward.small.wait', 3600) - time()
            );

            try {
                $chest2stuff = [
                    random_int(
                        config('game.reward.big.orbs.min', 2000),
                        config('game.reward.big.orbs.max', 4000)
                    ),
                    random_int(
                        config('game.reward.big.diamonds.min', 20),
                        config('game.reward.big.diamonds.max', 100)
                    ),
                    random_int(
                        config('game.reward.big.shards.min', 1),
                        config('game.reward.big.shards.max', 6)
                    ),
                    random_int(
                        config('game.reward.big.keys.min', 1),
                        config('game.reward.big.keys.max', 6)
                    )
                ];
            } catch (Exception $e) {
                return ResponseCode::UNHANDLED_EXCEPTION;
            }

            switch ($data['rewardType']) {
                case RewardType::GET:
                    break;
                case RewardType::OPEN_SMALL:
                    $chest1time = config('game.reward.small.wait', 3600);

                    $userScore->chest1time = now();
                    $userScore->chest1count++;
                    $userScore->save();
                    break;
                case RewardType::OPEN_BIG:
                    $chest2time = config('game.reward.big.wait', 14400);

                    $userScore->chest2time = now();
                    $userScore->chest2count++;
                    $userScore->save();
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            $chk = $hash->decodeChk(
                substr($data['chk'], 5),
                $hash->keys['reward']
            );

            $rewardResult = $hash->encodeChk(
                implode(':', [
                    Str::random(5),
                    $user->id,
                    $chk,
                    $data['udid'] ?? $user->udid,
                    $user->account->id ?? 0,
                    $chest1time,
                    implode(',', $chest1stuff),
                    $userScore->chest1count,
                    $chest2time,
                    implode(',', $chest2stuff),
                    $userScore->chest2count,
                    $data['rewardType']
                ]),
                $hash->keys['reward']
            );

            $rewardHash = $hash->generateRewardHash($rewardResult);
            $randomString = Str::random(5);
            return "{$randomString}{$rewardResult}|{$rewardHash}";
        } catch (GameUserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }
}
