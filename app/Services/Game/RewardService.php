<?php

namespace App\Services\Game;

use App\Enums\Game\RewardType;
use Exception;
use GDCN\Hash\Components\Reward as RewardComponent;
use GDCN\Hash\Components\RewardChk as RewardChkComponent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RewardService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @throws Exception
     */
    public function get(RewardType $type, ?string $uuid, string $udid, int $accountID, string $chk): string
    {
        $user = $this->helper->resolveUser($uuid);

        $chest1config = config('game.reward.small');
        $chest1time = max(0, (optional($user->score->chest1time)->getTimestamp() ?? 0) + $chest1config['wait'] - time());

        $chest2config = config('game.reward.big');
        $chest2time = max(0, (optional($user->score->chest2time)->getTimestamp() ?? 0) + $chest2config['wait'] - time());

        $chest1stuff = [0, 0, 0, 0];
        $chest2stuff = [0, 0, 0, 0];
        switch ($type->value) {
            case RewardType::SMALL:
                $user->score->chest1time = now();
                $chest1time = $chest1config['wait'];
                ++$user->score->chest1count;
                $user->score->save();

                $chest1stuff = $this->getStuff($chest1config);
                break;
            case RewardType::BIG:
                $user->score->chest2time = now();
                $chest2time = $chest2config['wait'];
                ++$user->score->chest2count;
                $user->score->save();

                $chest2stuff = $this->getStuff($chest2config);
        }

        $result = implode(':', [
            Str::random(5),
            $user->id,
            app(RewardChkComponent::class)->decode($chk),
            $udid ?? $user->udid,
            $accountID ?? $user->account->id ?? 0,
            $chest1time,
            implode(',', $chest1stuff),
            $user->score->chest1count,
            $chest2time,
            implode(',', $chest2stuff),
            ++$user->score->chest2count,
            $type->value
        ]);

        Log::channel('gdcn')
            ->info('[Reward System] Action: Get Reward', [
                'userID' => $user->id,
                'type' => $type->value,
                'udid' => str_repeat('*', strlen($udid)),
                'accountID' => $accountID,
                'chk' => str_repeat('*', strlen($chk))
            ]);

        $rewardComponent = app(RewardComponent::class);
        return implode('|', [
            implode(null, [
                Str::random(5),
                $rewardString = $rewardComponent->encode($result)
            ]),
            $rewardComponent->generateHash($rewardString)
        ]);
    }

    /**
     * @throws Exception
     */
    protected function getStuff(array $config): array
    {
        return [
            random_int(
                $config['orbs']['min'],
                $config['orbs']['max']
            ),
            random_int(
                $config['diamonds']['min'],
                $config['diamonds']['max']
            ),
            random_int(
                $config['shards']['min'],
                $config['shards']['max']
            ),
            random_int(
                $config['keys']['min'],
                $config['keys']['max']
            )
        ];
    }
}
