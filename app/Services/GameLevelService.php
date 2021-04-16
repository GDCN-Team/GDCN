<?php

namespace App\Services;

use App\Models\GameLevel;

/**
 * Class GameLevelService
 * @package App\Services
 */
class GameLevelService
{
    /**
     * @param GameLevel $level
     * @param int $songID
     * @return bool
     */
    public function setSong(GameLevel $level, int $songID): bool
    {
        $level->song = $songID;
        return $level->save();
    }
}
