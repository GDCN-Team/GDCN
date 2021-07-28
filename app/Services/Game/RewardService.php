<?php

namespace App\Services\Game;

use App\Enums\Game\RewardType;
use App\Exceptions\Game\UserNotFoundException;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use Exception;
use GDCN\Hash\Hasher;
use Illuminate\Support\Str;

/**
 * Class RewardService
 * @package App\Services\Game
 */
class RewardService
{
    public function __construct(
        public HelperService $helper,
        public Hasher $hash
    )
    {
    }

    /**
     * @param RewardType $type
     * @param $user
     * @param int $gameVersion
     * @param int $binaryVersion
     * @param string $udid
     * @param int $accountID
     * @param string $chk
     * @return string
     * @throws Exception
     */
    public function get(RewardType $type, $user, int $gameVersion, int $binaryVersion, string $udid, int $accountID, string $chk): string
    {
        if (!$user) {
            throw new UserNotFoundException();
        }

        $user = $this->helper->getModel($user, User::class);
        if (!$score = $user->score) {
            $score = new UserScore();
            $score->user = $user->id;
            $score->game_version = $gameVersion;
            $score->binary_version = $binaryVersion;
            $score->save();
        }

        $chest1config = config('game.reward.small');
        $chest1time = max(0, (optional($score->chest1time)->getTimestamp() ?? 0) + $chest1config['wait'] - time());

        $chest2config = config('game.reward.big');
        $chest2time = max(0, (optional($score->chest2time)->getTimestamp() ?? 0) + $chest2config['wait'] - time());

        $chest1stuff = [0, 0, 0, 0];
        $chest2stuff = [0, 0, 0, 0];
        switch ($type->value) {
            case RewardType::SMALL:
                ++$score->chest1time;
                $score->save();

                $chest1stuff = $this->getStuff($chest1config);
                break;
            case RewardType::BIG:
                ++$score->chest2time;
                $score->save();

                $chest2stuff = $this->getStuff($chest2config);
        }

        $result = implode(':', [
            Str::random(5),
            $user->id,
            $this->hash->decodeRewardChk($chk),
            $udid ?? $user->udid,
            $accountID ?? $user->account->id ?? 0,
            $chest1time,
            implode(',', $chest1stuff),
            ++$score->chest1count,
            $chest2time,
            implode(',', $chest2stuff),
            ++$score->chest2count,
            $type->value
        ]);

        $result = $this->hash->encodeRewardString($result);
        return Str::random(5) . $result . '|' . $this->hash->generateHashForReward($result);
    }

    /**
     * @param array $config
     * @return array
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
