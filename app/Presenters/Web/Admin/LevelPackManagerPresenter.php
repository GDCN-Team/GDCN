<?php

namespace App\Presenters\Web\Admin;

use App\Models\Game\Level;
use App\Models\Game\Level\Pack;
use Inertia\Inertia;
use Inertia\Response;

class LevelPackManagerPresenter
{
    public function renderListPage(array $props = []): Response
    {
        Inertia::share('packs', function () {
            return Pack::paginate(columns: ['id', 'name', 'stars', 'coins', 'difficulty', 'levels', 'bar_color', 'text_color', 'created_at', 'updated_at']);
        });

        Inertia::share('levels', function () {
            $packs = Inertia::getShared('packs')()->items();
            $levels = [];

            foreach ($packs as $pack) {
                foreach (explode(',', $pack['levels']) as $level) {
                    $levels[] = $level;
                }
            }

            return Level::findMany($levels, ['id', 'name'])->pluck('name', 'id');
        });

        return Inertia::render('Admin/Level/PackManager/List', $props);
    }

    public function renderManagePage(Pack $pack, array $props = []): Response
    {
        Inertia::share('pack', function () use ($pack) {
            return $pack->only(['id', 'name', 'stars', 'coins', 'difficulty', 'levels', 'bar_color', 'text_color', 'created_at', 'updated_at']);
        });

        Inertia::share('levels', function () use ($pack) {
            return Level::findMany(explode(',', $pack->levels), ['id', 'name'])->pluck('name', 'id');
        });

        return Inertia::render('Admin/Level/PackManager/Manage', $props);
    }
}
