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
            return Gauntlet::with(['level1:id,name', 'level2:id,name', 'level3:id,name', 'level4:id,name', 'level5:id,name'])->paginate(columns: ['id', 'gauntlet_id', 'level1', 'level2', 'level3', 'level4', 'level5', 'created_at', 'updated_at']);
        });

        return Inertia::render('Admin/Level/GauntletManager/List', $props);
    }

    public function renderManagePage(Gauntlet $gauntlet, array $props = []): Response
    {
        Inertia::share('gauntlet', function () use ($gauntlet) {
            $gauntlet->select(['id', 'gauntlet_id', 'level1', 'level2', 'level3', 'level4', 'level5', 'created_at', 'updated_at']);
            return $gauntlet->load(['level1:id,name', 'level2:id,name', 'level3:id,name', 'level4:id,name', 'level5:id,name']);
        });

        return Inertia::render('Admin/Level/GauntletManager/Manage', $props);
    }
}
