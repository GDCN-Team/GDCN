<?php

namespace App\Services\Game;

use App\Models\Game\Level;

/**
 * Class LevelService
 * @package App\Services
 */
class LevelService
{
    /**
     * @param Level $level
     * @param int $songID
     * @return bool
     */
    public function setSong(Level $level, int $songID): bool
    {
        $level->song = $songID;
        return $level->save();
    }
}
