<?php

namespace App\Presenters\Web\Admin;

use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Group;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class GroupManagerPresenter
{
    public function renderGroupListPage(array $props = []): Response
    {
        Inertia::share('groups', function () {
            return Group::paginate();
        });

        return Inertia::render('Admin/GroupManager/List', $props);
    }

    public function renderGroupManagePage(Group $group = null, array $props = []): Response
    {
        $shared = [
            'group' => function () use ($group) {
                return $group?->load(['members', 'flags']);
            },
            'accounts' => Inertia::lazy(function () {
                return Account::where('name', 'LIKE', '%' . Request::get('accountSearchText') . '%')->paginate();
            }),
            'flags' => Inertia::lazy(function () {
                return Account\Permission\Flag::where('name', 'LIKE', '%' . Request::get('flagSearchText') . '%')->paginate();
            })
        ];

        return Inertia::render('Admin/GroupManager/Manage', $shared + $props);
    }
}
