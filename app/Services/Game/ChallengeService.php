<?php

namespace App\Services\Game;

use App\Exceptions\Game\ChallengeGenerateException;
use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Challenge;
use Exception;
use GDCN\Hash\Hasher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class ChallengeService
 * @package App\Services\Game
 */
class ChallengeService
{
    protected array $aliases = [
        1 => 'orbs',
        2 => 'coins',
        3 => 'stars'
    ];

    /**
     * ChallengeService constructor.
     * @param Hasher $hash
     */
    public function __construct(
        public Hasher $hash
    )
    {
    }

    /**
     * @param string $udid
     * @param int $accountID
     * @param string $chk
     * @param bool $noCycle
     * @return string
     * @throws ChallengeGenerateException
     * @throws InvalidArgumentException
     */
    public function get(string $udid, int $accountID, string $chk, bool $noCycle = false): string
    {
        /* Generate challenges */
        $today = Carbon::today();
        $challenges = Challenge::query()
            ->where('updated_at', '>', $today)
            ->take(3)
            ->get();

        $challengeCount = $challenges->count();
        if ($challengeCount < 3) {
            $need = (3 - $challengeCount);
            for ($i = 0; $i < $need; $i++) {
                try {
                    $type = random_int(1, 3);

                    $name = $this->aliases[$type];

                    $config = config('game.challenge.' . $name);
                    $collectConfig = $config['collect'];
                    [$every, $reward] = $config['proportion'];

                    $collect = random_int($collectConfig['min'], $collectConfig['max']);
                    $challenge = new Challenge();
                    $challenge->type = $type;
                    $challenge->name = $name;
                    $challenge->collect_count = $collect;
                    $challenge->reward_count = ($collect / $every) * $reward;
                    $challenge->save();
                } catch (Exception) {
                    throw new InvalidArgumentException('Unknown Exception');
                }
            }

            if ($noCycle) {
                throw new ChallengeGenerateException();
            } else {
                return $this->get($udid, $accountID, $chk, true);
            }
        }

        $result = implode(':', [
            Str::random(5),
            $udid,
            $this->hash->decodeChallengeChk($chk),
            $udid,
            $accountID,
            Carbon::now()->secondsUntilEndOfDay(),
            $this->generateChallengeInfo($challenges[0]),
            $this->generateChallengeInfo($challenges[1]),
            $this->generateChallengeInfo($challenges[2])
        ]);

        $result = $this->hash->encodeChallengeString($result);
        return Str::random(5) . $result . '|' . $this->hash->generateHashForChallenge($result);
    }

    /**
     * @param Challenge $challenge
     * @return string
     */
    public function generateChallengeInfo(Challenge $challenge): string
    {
        $challenge = $challenge->toArray();
        return "{$challenge['id']},{$challenge['type']},{$challenge['collect_count']},{$challenge['reward_count']},{$challenge['name']}";
    }
}
