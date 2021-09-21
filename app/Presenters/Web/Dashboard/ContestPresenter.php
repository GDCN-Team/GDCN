<?php

namespace App\Presenters\Web\Dashboard;

use App\Models\Game\Account;
use App\Models\Game\Level\Contest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ContestPresenter
{
    public function renderListPage(array $props = []): Response
    {
        Inertia::share('contests', function () {
            return Contest::with(['account'])->paginate();
        });

        return Inertia::render('Dashboard/Contest/List', $props);
    }

    public function renderInfoPage(Contest $contest, array $props = []): Response
    {
        Inertia::share('contest', function () use ($contest) {
            return $contest->load(['account']);
        });

        Inertia::share('levels', function () use ($contest) {
            return $contest->levels()
                ->with(['flags', 'level.user'])
                ->paginate();
        });

        return Inertia::render('Dashboard/Contest/Info', $props);
    }

    public function renderJoinPage(Contest $contest, array $props = []): Response
    {
        Inertia::share('contest', $contest);

        Inertia::share('levels', function () {
            /** @var Account $account */
            $account = Auth::user();

            return $account->user?->levels;
        });

        return Inertia::render('Dashboard/Contest/Join', $props);
    }
}
