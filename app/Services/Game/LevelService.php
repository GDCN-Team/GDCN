<?php

namespace App\Services\Game;

use App\Models\GameLevel;

/**
 * Class LevelService
 * @package App\Services
 */
class LevelService
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
