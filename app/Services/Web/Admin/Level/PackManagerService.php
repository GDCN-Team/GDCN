<?php

namespace App\Services\Web\Admin\Level;

use App\Exceptions\Web\Admin\LevelPackManager\CreateException;
use App\Exceptions\Web\Admin\LevelPackManager\UpdateException;
use App\Models\Game\Level\Pack;
use App\Services\Web\NotificationService;

class PackManagerService
{
    public function __construct(
        public NotificationService $notification
    )
    {
    }

    /**
     * @throws CreateException
     */
    public function createLevelPack(string $name, array $levels, int $stars, ?int $coins, int $difficulty, string $text_color, string $bar_color = null): Pack
    {
        $text_color = substr($text_color, 4, -1);
        $bar_color = substr($bar_color, 4, -1);

        if (is_null($coins)) {
            $coins = 0;
        }

        if (empty($levels)) {
            throw new CreateException('关卡不可为空');
        }

        return Pack::create([
            'name' => $name,
            'levels' => implode(',', $levels),
            'stars' => $stars,
            'coins' => $coins,
            'difficulty' => $difficulty,
            'text_color' => $text_color,
            'bar_color' => $bar_color
        ]);
    }

    public function deleteLevelPack(Pack $pack): bool
    {
        return $pack->delete();
    }

    /**
     * @throws UpdateException
     */
    public function updateLevelPack(Pack $pack, string $name, array $levels, int $stars, ?int $coins, int $difficulty, string $text_color, string $bar_color = null): bool
    {
        $text_color = substr($text_color, 4, -1);
        $bar_color = substr($bar_color, 4, -1);

        if (is_null($coins)) {
            $coins = 0;
        }

        if (empty($levels)) {
            throw new UpdateException('关卡不可为空');
        }

        return $pack->update([
            'name' => $name,
            'levels' => implode(',', $levels),
            'stars' => $stars,
            'coins' => $coins,
            'difficulty' => $difficulty,
            'text_color' => $text_color,
            'bar_color' => $bar_color
        ]);
    }
}
