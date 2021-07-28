<?php

namespace App\Policies\Game;

use App\Models\Game\Level;
use App\Models\Game\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LevelPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Level $level
     * @return bool
     */
    public function update(User $user, Level $level): bool
    {
        return $level->user === $user->id;
    }

    /**
     * @param User $user
     * @param Level $level
     * @return bool
     */
    public function delete(User $user, Level $level): bool
    {
        return $level->user === $user->id;
    }
}
