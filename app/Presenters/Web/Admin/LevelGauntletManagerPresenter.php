<?php

namespace App\Presenters\Web\Admin;

use App\Models\Game\Level\Gauntlet;
use Inertia\Inertia;
use Inertia\Response;

class LevelGauntletManagerPresenter
{
    public function renderListPage(array $props = []): Response
    {
        Inertia::share('gauntlets', function () {
            return Gauntlet::with(['level1.user', 'level2.user', 'level3.user', 'level4.user', 'level5.user'])->paginate();
        });

        return Inertia::render('Admin/Level/Gauntlet/List', $props);
    }

    public function renderManagePage(Gauntlet $gauntlet, array $props = []): Response
    {
        Inertia::share('gauntlet', function () use ($gauntlet) {
            return $gauntlet->load(['level1.user', 'level2.user', 'level3.user', 'level4.user', 'level5.user']);
        });

        return Inertia::render('Admin/Level/Gauntlet/Manage', $props);
    }
}
