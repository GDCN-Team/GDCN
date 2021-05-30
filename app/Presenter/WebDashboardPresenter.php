<?php

namespace App\Presenter;

use App\Models\GameAccount;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Class WebDashboardPresenter
 * @package App\Presenter
 */
class WebDashboardPresenter
{
    /**
     * @var WebAuthPresenter
     */
    protected $authPresenter;

    /**
     * WebDashboardPresenter constructor.
     * @param WebAuthPresenter $authPresenter
     */
    public function __construct(WebAuthPresenter $authPresenter)
    {
        $this->authPresenter = $authPresenter;
    }

    /**
     * @return InertiaResponse
     */
    public function home(): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        return Inertia::render('Dashboard/Home', [
            'account' => $account,
            'user' => $account->user ?? null,
            'friends' => $account->friends,
            'messages' => $account->messages
        ]);
    }

    /**
     * @return InertiaResponse
     */
    public function profile(): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        return Inertia::render('Dashboard/Profile', [
            'account' => $account,
            'user' => $account->user ?? null,
            'friends' => $account->friends,
            'messages' => $account->messages
        ]);
    }

    /**
     * @return InertiaResponse
     */
    public function setting(): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        return Inertia::render('Dashboard/Setting', [
            'account' => $account,
            'user' => $account->user ?? null,
            'friends' => $account->friends,
            'messages' => $account->messages
        ]);
    }

    /**
     * @return InertiaResponse
     */
    public function passwordChange(): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        return Inertia::render('Dashboard/ChangePassword', [
            'account' => $account,
            'user' => $account->user ?? null,
            'friends' => $account->friends,
            'messages' => $account->messages
        ]);
    }
}
