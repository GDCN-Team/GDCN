<?php

namespace App\Presenters\Web;

use App\Enums\Game\BanType;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Account\Permission\Assign as AccountPermissionAssign;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Level\Pack as LevelPack;
use App\Models\Game\Level\Rating as LevelRating;
use App\Models\Game\UserScore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardPresenter
{
    /**
     * @param array $props
     * @return Response
     */
    public function renderHomePage(array $props = []): Response
    {
        Inertia::share([
            'dynamic.accounts_count' => Account::count(),
            'dynamic.levels_count' => Level::count(),
            'dynamic.level_packs_count' => LevelPack::count(),
            'dynamic.comments_count' => array_sum([
                AccountComment::count(),
                LevelComment::count()
            ]),
            'dynamic.moderators_count' => AccountPermissionAssign::count(),
            'dynamic.new_accounts' => Account::query()
                ->orderByDesc('created_at')
                ->paginate(columns: ['id', 'name', 'created_at']),
            'dynamic.new_levels' => Level::query()
                ->with('user:id,name')
                ->orderByDesc('created_at')
                ->paginate(columns: ['id', 'name', 'user', 'created_at']),
            'dynamic.new_rated_levels' => LevelRating::query()
                ->with(['level:id,name,user', 'level.user:id,name'])
                ->orderByDesc('created_at')
                ->paginate(columns: ['level', 'created_at']),
            'dynamic.new_rated_featured_levels' => LevelRating::query()
                ->with(['level:id,name,user', 'level.user:id,name'])
                ->where('featured_score', '>', 0)
                ->orderByDesc('created_at')
                ->paginate(columns: ['level', 'created_at']),
            'dynamic.new_rated_epic_levels' => LevelRating::query()
                ->with(['level:id,name,user', 'level.user:id,name'])
                ->whereEpic(true)
                ->orderByDesc('created_at')
                ->paginate(columns: ['level', 'created_at']),
            'dynamic.top_stars' => UserScore::query()
                ->whereDoesntHave('user.ban', function (Builder $query) {
                    return $query->where('type', BanType::BAN);
                })->with('user:id,name')
                ->orderByDesc('stars')
                ->orderByDesc('created_at')
                ->paginate(columns: ['user', 'stars']),
            'dynamic.top_diamonds' => UserScore::query()
                ->whereDoesntHave('user.ban', function (Builder $query) {
                    return $query->where('type', BanType::BAN);
                })->with('user:id,name')
                ->orderByDesc('diamonds')
                ->orderByDesc('created_at')
                ->paginate(columns: ['user', 'diamonds']),
            'dynamic.top_demons' => UserScore::query()
                ->whereDoesntHave('user.ban', function (Builder $query) {
                    return $query->where('type', BanType::BAN);
                })->with('user:id,name')
                ->orderByDesc('demons')
                ->orderByDesc('created_at')
                ->paginate(columns: ['user', 'demons']),
            'dynamic.top_creator_points' => UserScore::query()
                ->whereDoesntHave('user.ban', function (Builder $query) {
                    return $query->where('type', BanType::BAN);
                })->with('user:id,name')
                ->orderByDesc('creator_points')
                ->orderByDesc('created_at')
                ->paginate(columns: ['user', 'creator_points'])
        ]);

        return Inertia::render('Dashboard/Home', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderProfilePage(array $props = []): Response
    {
        Inertia::share('account', function () {
            /** @var Account $account */
            $account = Auth::user();

            return $account->load('user');
        });

        return Inertia::render('Dashboard/Profile', $props);
    }

    /**
     * @param Account $account
     * @param array $props
     * @return Response
     */
    public function renderAccountInfoPage(Account $account, array $props = []): Response
    {
        $account->load(['user.score', 'comments']);

        Inertia::share('account', $account);
        return Inertia::render('Dashboard/AccountInfo', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderProfileSettingPage(array $props = []): Response
    {
        return Inertia::render('Dashboard/AccountSetting', $props);
    }

    /**
     * @param Level $level
     * @param array $props
     * @return Response
     */
    public function renderLevelInfoPage(Level $level, array $props = []): Response
    {
        $level->load(['user', 'comments.account']);

        Inertia::share('level', $level);
        return Inertia::render('Dashboard/LevelInfo', $props);
    }
}
