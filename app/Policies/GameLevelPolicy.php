<?php

namespace App\Policies;

use App\Models\GameLevel;
use App\Models\GameLog;
use App\Models\GameUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameLevelPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameUser $user
     * @param GameLevel $level
     * @return bool
     */
    public function update(GameUser $user, GameLevel $level): bool
    {
        return $level->user === $user->id;
    }

    /**
     * @param GameUser $user
     * @param GameLevel $level
     * @return bool
     */
    public function delete(GameUser $user, GameLevel $level): bool
    {
        return $level->user === $user->id;
    }
}
