<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Assign as GroupAssign;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\FlagAssign;
use App\Models\Game\Account\Permission\Group;

class PermissionService
{
    public function addGroup(string $name, int $modLevel, string $commentColor = '255,255,255'): Group
    {
        return Group::create([
            'name' => $name,
            'mod_level' => $modLevel,
            'comment_color' => $commentColor
        ]);
    }

    public function addFlag(string $name): Flag
    {
        return Flag::create([
            'name' => $name
        ]);
    }

    public function addFlagToGroup(Flag $flag, Group $group): FlagAssign
    {
        return FlagAssign::create([
            'group' => $group->id,
            'flag' => $flag->id
        ]);
    }

    public function addAccountToGroup(Account $account, Group $group): GroupAssign
    {
        return GroupAssign::create([
            'account' => $account->id,
            'group' => $group->id
        ]);
    }
}
