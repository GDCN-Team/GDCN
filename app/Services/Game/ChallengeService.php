<?php

namespace App\Services\Game;

use App\Models\Game\Challenge;
use Exception;
use GDCN\Hash\Components\Challenge as ChallengeComponent;
use GDCN\Hash\Components\ChallengeChk as ChallengeChkComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChallengeService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    protected array $aliases = [
        1 => 'orbs',
        2 => 'coins',
        3 => 'stars'
    ];

    /**
     * @throws Exception
     */
    public function get(?string $uuid, string $udid, int $accountID, string $chk): string
    {
        $user = $this->helper->resolveUser($uuid);

        $challenges = Challenge::whereDate('created_at', now())
            ->take(3)
            ->get();

        if ($challenges->count() < 3) {
            $need = 3 - $challenges->count();
            for ($i = 0; $i < $need; $i++) {
                $type = random_int(1, 3);
                $config = config('game.challenge.' . $this->aliases[$type]);
                $collect = random_int($config['collect']['min'], $config['collect']['max']);
                [$every, $give] = $config['proportion'];
                $reward = round($collect / $every) * $give;

                $challenges->push(
                    Challenge::create([
                        'type' => $type,
                        'name' => Arr::random($config['names']),
                        'collect_count' => $collect,
                        'reward_count' => $reward
                    ])
                );
            }
        }

        $challengeComponent = app(ChallengeComponent::class);
        $result = implode(':', [
            Str::random(5),
            $user->id,
            app(ChallengeChkComponent::class)->decode($chk),
            $udid,
            $accountID,
            app(Carbon::class)->secondsUntilEndOfDay(),
            $this->generateChallengeInfo($challenges[0]),
            $this->generateChallengeInfo($challenges[1]),
            $this->generateChallengeInfo($challenges[2])
        ]);

        Log::channel('gdcn')
            ->info('[Challenge System] Action: Get Challenges', [
                'uuid' => str_repeat('*', strlen($uuid)),
                'udid' => str_repeat('*', strlen($udid)),
                'accountID' => $accountID,
                'chk' => str_repeat('*', strlen($chk))
            ]);

        return implode('|', [
            implode(null, [
                Str::random(5),
                $challengeString = $challengeComponent->encode($result)
            ]),
            $challengeComponent->generateHash($challengeString)
        ]);
    }

    public function generateChallengeInfo(Challenge $challenge): string
    {
        return implode(',', [
            $challenge->id,
            $challenge->type,
            $challenge->collect_count,
            $challenge->reward_count,
            $challenge->name
        ]);
    }
}
