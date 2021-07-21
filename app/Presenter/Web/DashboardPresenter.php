<?php

namespace App\Presenter\Web;

use App\Models\Game\Account;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Class DashboardPresenter
 * @package App\Presenter
 */
class DashboardPresenter
{
    /**
     * @var AuthPresenter
     */
    protected $authPresenter;

    /**
     * DashboardPresenter constructor.
     * @param AuthPresenter $authPresenter
     */
    public function __construct(AuthPresenter $authPresenter)
    {
        $this->authPresenter = $authPresenter;
    }

    /**
     * @return InertiaResponse
     */
    public function home(): InertiaResponse
    {
        /** @var Account $account */
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
        /** @var Account $account */
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
        /** @var Account $account */
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
        /** @var Account $account */
        $account = Auth::user();

        return Inertia::render('Dashboard/ChangePassword', [
            'account' => $account,
            'user' => $account->user ?? null,
            'friends' => $account->friends,
            'messages' => $account->messages
        ]);
    }
}
