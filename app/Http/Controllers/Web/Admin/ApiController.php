<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\Web\Admin\GroupManager\AddFlagException;
use App\Exceptions\Web\Admin\GroupManager\AddMemberException;
use App\Exceptions\Web\Admin\LevelPackManager\CreateException;
use App\Exceptions\Web\Admin\LevelPackManager\UpdateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\GroupCreateRequest;
use App\Http\Requests\Web\Admin\GroupUpdateRequest;
use App\Http\Requests\Web\Admin\Level\GauntletCreateRequest;
use App\Http\Requests\Web\Admin\Level\GauntletUpdateRequest;
use App\Http\Requests\Web\Admin\Level\PackCreateRequest;
use App\Http\Requests\Web\Admin\Level\PackUpdateRequest;
use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Level\Gauntlet;
use App\Models\Game\Level\Pack;
use App\Services\Web\Admin\GroupManagerService;
use App\Services\Web\Admin\Level\GauntletManagerService;
use App\Services\Web\Admin\Level\PackManagerService;
use Illuminate\Http\RedirectResponse;

class ApiController extends Controller
{
    public function __construct(
        public GroupManagerService    $groupManagerService,
        public GauntletManagerService $gauntletManagerService,
        public PackManagerService     $packManagerService
    )
    {
    }

    public function addMemberToGroup(Group $group, Account $account): RedirectResponse
    {
        try {
            $assign = $this->groupManagerService->addMemberToGroup($group, $account);
            $this->groupManagerService->notification->sendMessage('success', "添加成功! assignID: $assign->id");
        } catch (AddMemberException $e) {
            $this->groupManagerService->notification->sendMessage('error', $e->getMessage());
        }

        return back();
    }

    public function deleteMemberFromGroup(Group $group, Account $account): RedirectResponse
    {
        if ($this->groupManagerService->deleteMemberFromGroup($group, $account)) {
            $this->groupManagerService->notification->sendMessage('success', '删除成功!');
        } else {
            $this->groupManagerService->notification->sendMessage('success', '删除失败');
        }

        return back();
    }

    public function addFlagToGroup(Group $group, Flag $flag): RedirectResponse
    {
        try {
            $assign = $this->groupManagerService->addFlagToGroup($group, $flag);
            $this->groupManagerService->notification->sendMessage('success', "添加成功! assignID: $assign->id");
        } catch (AddFlagException $e) {
            $this->groupManagerService->notification->sendMessage('error', $e->getMessage());
        }

        return back();
    }

    public function deleteFlagToGroup(Group $group, Flag $flag): RedirectResponse
    {
        if ($this->groupManagerService->deleteFlagFromGroup($group, $flag)) {
            $this->groupManagerService->notification->sendMessage('success', '删除成功!');
        } else {
            $this->groupManagerService->notification->sendMessage('error', '删除失败');
        }

        return back();
    }

    public function createGroup(GroupCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($group = $this->groupManagerService->createGroup($data['name'], $data['mod_level'], $data['comment_color'])) {
            $this->groupManagerService->notification->sendMessage('success', "创建成功! ID: $group->id");
        } else {
            $this->groupManagerService->notification->sendMessage('error', "创建失败");
        }

        return back();
    }

    public function deleteGroup(Group $group): RedirectResponse
    {
        if ($this->groupManagerService->deleteGroup($group)) {
            $this->groupManagerService->notification->sendMessage('success', '删除成功!');
        } else {
            $this->groupManagerService->notification->sendMessage('error', '删除失败');
        }

        return back();
    }

    public function updateGroup(Group $group, GroupUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->gauntletManagerService->updateGroup($group, $data['name'], $data['mod_level'], $data['comment_color'])) {
            $this->gauntletManagerService->notification->sendMessage('success', '更新成功!');
        } else {
            $this->gauntletManagerService->notification->sendMessage('error', '更新失败');
        }

        return back();
    }

    public function deleteLevelGauntlet(Gauntlet $gauntlet): RedirectResponse
    {
        if ($this->gauntletManagerService->deleteLevelGauntlet($gauntlet)) {
            $this->gauntletManagerService->notification->sendMessage('success', '删除成功!');
        } else {
            $this->gauntletManagerService->notification->sendMessage('error', '删除失败');
        }

        return back();
    }

    public function updateLevelGauntlet(Gauntlet $gauntlet, GauntletUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->gauntletManagerService->updateLevelGauntlet($gauntlet, $data['type'], $data['level1'], $data['level2'], $data['level3'], $data['level4'], $data['level5'])) {
            $this->gauntletManagerService->notification->sendMessage('success', '更新成功!');
        } else {
            $this->gauntletManagerService->notification->sendMessage('error', '更新失败');
        }

        return back();
    }

    public function createLevelGauntlet(GauntletCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($gauntlet = $this->gauntletManagerService->createLevelGauntlet($data['gauntlet_id'], $data['level1'], $data['level2'], $data['level3'], $data['level4'], $data['level5'])) {
            $this->gauntletManagerService->notification->sendMessage('success', "创建成功! gauntletID: $gauntlet->id");
        } else {
            $this->gauntletManagerService->notification->sendMessage('error', '创建失败');
        }

        return back();
    }

    public function createLevelPack(PackCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {
            if ($pack = $this->packManagerService->createLevelPack($data['name'], $data['levels'], $data['stars'], $data['coins'], $data['difficulty'], $data['text_color'], $data['bar_color'])) {
                $this->packManagerService->notification->sendMessage('success', "创建成功! ID: $pack->id");
            } else {
                $this->packManagerService->notification->sendMessage('error', "创建失败");
            }
        } catch (CreateException $e) {
            $this->packManagerService->notification->sendMessage('error', "创建失败, 原因: " . $e->getMessage());
        }

        return back();
    }

    public function deleteLevelPack(Pack $pack): RedirectResponse
    {
        if ($this->packManagerService->deleteLevelPack($pack)) {
            $this->packManagerService->notification->sendMessage('success', '删除成功!');
        } else {
            $this->packManagerService->notification->sendMessage('error', '删除失败');
        }

        return back();
    }

    public function updateLevelPack(Pack $pack, PackUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {
            if ($this->packManagerService->updateLevelPack($pack, $data['name'], $data['levels'], $data['stars'], $data['coins'], $data['difficulty'], $data['text_color'], $data['bar_color'])) {
                $this->packManagerService->notification->sendMessage('success', '更新成功!');
            } else {
                $this->packManagerService->notification->sendMessage('error', '更新失败');
            }
        } catch (UpdateException $e) {
            $this->packManagerService->notification->sendMessage('error', "更新失败, 原因: " . $e->getMessage());
        }

        return back();
    }
}
