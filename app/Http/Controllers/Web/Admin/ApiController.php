<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\Web\Admin\GroupManager\AddFlagException;
use App\Exceptions\Web\Admin\GroupManager\AddMemberException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\GroupCreateRequest;
use App\Http\Requests\Web\Admin\GroupUpdateRequest;
use App\Http\Requests\Web\Admin\Level\GauntletCreateRequest;
use App\Http\Requests\Web\Admin\Level\GauntletUpdateRequest;
use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Level\Gauntlet;
use App\Services\Web\Admin\GroupManagerService;
use Illuminate\Http\RedirectResponse;

class ApiController extends Controller
{
    public function __construct(
        public GroupManagerService $service
    )
    {
    }

    public function addMemberToGroup(Group $group, Account $account): RedirectResponse
    {
        try {
            $assign = $this->service->addMemberToGroup($group, $account);
            $this->service->notification->sendMessage('success', "添加成功! assignID: $assign->id");
        } catch (AddMemberException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
        }

        return back();
    }

    public function deleteMemberFromGroup(Group $group, Account $account): RedirectResponse
    {
        if ($this->service->deleteMemberFromGroup($group, $account)) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('success', '操作失败');
        }

        return back();
    }

    public function addFlagToGroup(Group $group, Flag $flag): RedirectResponse
    {
        try {
            $assign = $this->service->addFlagToGroup($group, $flag);
            $this->service->notification->sendMessage('success', "添加成功! assignID: $assign->id");
        } catch (AddFlagException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
        }

        return back();
    }

    public function deleteFlagToGroup(Group $group, Flag $flag): RedirectResponse
    {
        if ($this->service->deleteFlagFromGroup($group, $flag)) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }

    public function createGroup(GroupCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($group = $this->service->createGroup($data['name'], $data['mod_level'], $data['comment_color'])) {
            $this->service->notification->sendMessage('success', "创建成功! ID: $group->id");
        } else {
            $this->service->notification->sendMessage('error', "创建失败");
        }

        return back();
    }

    public function deleteGroup(Group $group): RedirectResponse
    {
        if ($this->service->deleteGroup($group)) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }

    public function updateGroup(Group $group, GroupUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->service->updateGroup($group, $data['name'], $data['mod_level'], $data['comment_color'])) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }

    public function deleteLevelGauntlet(Gauntlet $gauntlet): RedirectResponse
    {
        if ($this->service->deleteLevelGauntlet($gauntlet)) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }

    public function updateLevelGauntlet(Gauntlet $gauntlet, GauntletUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->service->updateLevelGauntlet($gauntlet, $data['type'], $data['level1'], $data['level2'], $data['level3'], $data['level4'], $data['level5'])) {
            $this->service->notification->sendMessage('success', '操作成功!');
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }

    public function createLevelGauntlet(GauntletCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($gauntlet = $this->service->createLevelGauntlet($data['gauntlet_id'], $data['level1'], $data['level2'], $data['level3'], $data['level4'], $data['level5'])) {
            $this->service->notification->sendMessage('success', "操作成功! gauntletID: $gauntlet->id");
        } else {
            $this->service->notification->sendMessage('error', '操作失败');
        }

        return back();
    }
}
