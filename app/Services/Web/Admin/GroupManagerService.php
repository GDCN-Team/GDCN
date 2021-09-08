<?php

namespace App\Services\Web\Admin;

use App\Exceptions\Web\Admin\GroupManager\AddFlagException;
use App\Exceptions\Web\Admin\GroupManager\AddMemberException;
use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Assign as AccountPermissionGroupAssign;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\FlagAssign;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Level\Gauntlet;
use App\Presenters\Web\Admin\GroupManagerPresenter;
use App\Services\Web\NotificationService;

class GroupManagerService
{
    public function __construct(
        public GroupManagerPresenter $presenter,
        public NotificationService   $notification
    )
    {
    }

    /**
     * @throws AddMemberException
     */
    public function addMemberToGroup(Group $group, Account $account): AccountPermissionGroupAssign
    {
        if ($assign = AccountPermissionGroupAssign::whereAccount($account->id)->first()) {
            $assignedGroup = $assign->getRelationValue('group');
            throw new AddMemberException("$account->name 已被分配到组 $assignedGroup->name");
        }

        return AccountPermissionGroupAssign::create([
            'group' => $group->id,
            'account' => $account->id
        ]);
    }

    public function deleteMemberFromGroup(Group $group, Account $account): bool
    {
        return AccountPermissionGroupAssign::where([
            'group' => $group->id,
            'account' => $account->id
        ])->delete();
    }

    /**
     * @throws AddFlagException
     */
    public function addFlagToGroup(Group $group, Flag $flag): FlagAssign
    {
        if ($assign = FlagAssign::whereGroup($group->id)->whereFlag($flag->id)->first()) {
            $assignedGroup = $assign->getRelationValue('group');
            throw new AddFlagException("$flag->name 已分配到组 $assignedGroup->name, 请勿重复分配");
        }

        return FlagAssign::create([
            'group' => $group->id,
            'flag' => $flag->id
        ]);
    }

    public function deleteFlagFromGroup(Group $group, Flag $flag): bool
    {
        return FlagAssign::where([
            'group' => $group->id,
            'flag' => $flag->id
        ])->delete();
    }

    public function createGroup(string $name, int $mod_level, string $comment_color): Group
    {
        return Group::create([
            'name' => $name,
            'mod_level' => $mod_level,
            'comment_color' => str_replace(' ', '', substr($comment_color, 4, -1))
        ]);
    }

    public function deleteGroup(Group $group): bool
    {
        return $group->delete();
    }
}
