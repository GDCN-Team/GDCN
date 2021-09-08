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
            return Gauntlet::with(['level1.user', 'level2.user', 'level3.user', 'level4.user', 'level5.user'])->paginate(columns: ['id', 'gauntlet_id', 'level1', 'level2', 'level3', 'level4', 'level5', 'created_at', 'updated_at']);
        });

        return Inertia::render('Admin/Level/GauntletManager/List', $props);
    }

    public function renderManagePage(Gauntlet $gauntlet, array $props = []): Response
    {
        Inertia::share('gauntlet', function () use ($gauntlet) {
            $gauntlet->select(['id', 'gauntlet_id', 'level1', 'level2', 'level3', 'level4', 'level5', 'created_at', 'updated_at']);
            return $gauntlet->load(['level1.user', 'level2.user', 'level3.user', 'level4.user', 'level5.user']);
        });

        return Inertia::render('Admin/Level/GauntletManager/Manage', $props);
    }
}
