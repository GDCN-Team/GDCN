<?php

namespace App\Services\Web\Admin\Level;

use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Level\Gauntlet;
use App\Services\Web\NotificationService;

class GauntletManagerService
{
    public function __construct(
        public NotificationService $notification
    )
    {
    }

    public function updateGroup(Group $group, string $name, int $mod_level, string $comment_color): bool
    {
        return $group->update([
            'name' => $name,
            'mod_level' => $mod_level,
            'comment_color' => str_replace(' ', '', substr($comment_color, 4, -1))
        ]);
    }

    public function deleteLevelGauntlet(Gauntlet $gauntlet): bool
    {
        return $gauntlet->delete();
    }

    public function updateLevelGauntlet(Gauntlet $gauntlet, int $gauntlet_id, int $level1, int $level2, int $level3, int $level4, int $level5): bool
    {
        return $gauntlet->update([
            'gauntlet_id' => $gauntlet_id,
            'level1' => $level1,
            'level2' => $level2,
            'level3' => $level3,
            'level4' => $level4,
            'level5' => $level5
        ]);
    }

    public function createLevelGauntlet(int $gauntlet_id, int $level1, int $level2, int $level3, int $level4, int $level5): Gauntlet
    {
        return Gauntlet::create([
            'gauntlet_id' => $gauntlet_id,
            'level1' => $level1,
            'level2' => $level2,
            'level3' => $level3,
            'level4' => $level4,
            'level5' => $level5
        ]);
    }
}
