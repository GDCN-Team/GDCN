<?php

namespace App\Presenters\Web\Admin;

use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\Group;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class GroupManagerPresenter
{
    public function renderListPage(array $props = []): Response
    {
        Inertia::share('groups', function () {
            return Group::paginate(columns: ['id', 'name', 'mod_level', 'comment_color', 'created_at', 'updated_at']);
        });

        return Inertia::render('Admin/GroupManager/List', $props);
    }

    public function renderManagePage(Group $group = null, array $props = []): Response
    {
        $shared = [
            'group' => function () use ($group) {
                $group->select(['id', 'name', 'mod_level', 'comment_color', 'created_at', 'updated_at']);
                return $group?->load(['members:' . app(Account::class)->getTable() . '.id,name', 'flags:' . app(Flag::class)->getTable() . '.id,name']);
            },
            'accounts' => Inertia::lazy(function () {
                return Account::where('name', 'LIKE', '%' . Request::get('accountSearchText') . '%')->paginate(columns: ['id', 'name']);
            }),
            'flags' => Inertia::lazy(function () {
                return Flag::where('name', 'LIKE', '%' . Request::get('flagSearchText') . '%')->paginate(columns: ['id', 'name']);
            })
        ];

        return Inertia::render('Admin/GroupManager/Manage', $shared + $props);
    }
}
