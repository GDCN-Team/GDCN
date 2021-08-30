<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\Web\Admin\GroupManager\AddFlagException;
use App\Exceptions\Web\Admin\GroupManager\AddMemberException;
use App\Http\Controllers\Controller;
use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\Group;
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
            $this->service->notification->sendMessage('success', '操作失败');
        }

        return back();
    }
}
