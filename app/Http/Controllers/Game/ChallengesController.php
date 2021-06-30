<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Challenge\GetRequest;
use App\Models\GameChallenge;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class ChallengesController
 * @package App\Http\Controllers
 */
class ChallengesController extends Controller
{
    /**
     * @var string[]
     */
    protected $alias = [
        1 => 'orbs',
        2 => 'coins',
        3 => 'stars'
    ];

    /**
     * @param GetRequest $request
     * @param HashesController $hash
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJChallenges
     */
    public function get(GetRequest $request, HashesController $hash)
    {
        try {
            $data = $request->validated();

            /* Generate challenges */
            $today = Carbon::today();
            $challenges = GameChallenge::query()
                ->where('updated_at', '>', $today);

            $challengeCount = $challenges->count();
            if ($challengeCount < 3) {
                $need = (3 - $challengeCount);
                for ($i = 0; $i < $need; $i++) {
                    try {
                        $type = random_int(1, 3);

                        $name = $this->alias[$type];

                        $config = config('game.challenge.' . $name);
                        $collectConfig = $config['collect'];
                        [$every, $reward] = $config['proportion'];

                        $collect = random_int($collectConfig['min'], $collectConfig['max']);
                        $challenge = new GameChallenge();
                        $challenge->type = $type;
                        $challenge->name = $name;
                        $challenge->collect_count = $collect;
                        $challenge->reward_count = ($collect / $every) * $reward;
                        $challenge->save();
                    } catch (Exception $e) {
                        throw new InvalidArgumentException('Unknown Exception');
                    }
                }
            }

            $challenges = $challenges->get();
            $count = count($challenges);
            if ($count < 3) {
                return ResponseCode::CHALLENGE_NOT_ENOUGH;
            }

            $user = $request->getGameUser();
            if (!$user) {
                return ResponseCode::USER_NOT_FOUND;
            }

            $challengeInfo = [
                Str::random(5),
                $data['uuid'] ?: $user->id,

                $hash->decodeChk(
                    substr($data['chk'], 5),
                    $hash->keys['challenge']
                ),

                $data['udid'] ?? $user->udid,
                $data['accountID'] ?? 0,
                Carbon::now()->secondsUntilEndOfDay(),
                $this->generateChallengeInfo($challenges[0]),
                $this->generateChallengeInfo($challenges[1]),
                $this->generateChallengeInfo($challenges[2])
            ];

            $result = $hash->encodeChk(
                implode(':', $challengeInfo),
                $hash->keys['challenge']
            );

            $random = Str::random(5);
            return "{$random}{$result}|{$hash->generateChallengeHash($result)}";
        } catch (UserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        } catch (InvalidArgumentException $e) {
            return ResponseCode::UNHANDLED_EXCEPTION;
        }
    }

    /**
     * @param Arrayable $challenge
     * @return string
     */
    protected function generateChallengeInfo(Arrayable $challenge): string
    {
        return "{$challenge['id']},{$challenge['type']},{$challenge['collect_count']},{$challenge['reward_count']},{$challenge['name']}";
    }
}
