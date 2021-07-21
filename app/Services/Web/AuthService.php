<?php

namespace App\Services\Web;

use App\Presenter\Web\AuthPresenter;
use App\Presenter\Web\DashboardPresenter;
use App\Services\Game\AccountService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    /**
     * @var AuthPresenter
     */
    protected $presenter;

    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var AccountService
     */
    protected $gameAccountService;

    /**
     * @var DashboardPresenter
     */
    protected $dashboardPresenter;

    /**
     * AuthService constructor.
     * @param AuthPresenter $presenter
     * @param DashboardPresenter $dashboardPresenter
     * @param NoticeService $noticeService
     * @param AccountService $gameAccountService
     */
    public function __construct(AuthPresenter $presenter, DashboardPresenter $dashboardPresenter, NoticeService $noticeService, AccountService $gameAccountService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
        $this->dashboardPresenter = $dashboardPresenter;
        $this->gameAccountService = $gameAccountService;
    }

    /**
     * @param string $name
     * @param string $password
     * @param bool $remember
     * @return Response|InertiaResponse
     */
    public function login(string $name, string $password, bool $remember)
    {
        $result = Auth::attempt(compact('name', 'password'), $remember);
        return $result ? Inertia::location(Session::pull('url.intended', '/')) : $this->presenter->login(['errors' => ['password' => '密码错误']]);
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @return InertiaResponse
     */
    public function register(string $name, string $password, string $email): InertiaResponse
    {
        $account = $this->gameAccountService->register($name, $password, $email);
        Auth::login($account, true);

        $this->noticeService->sendSuccessNotice('注册成功!');
        return $this->dashboardPresenter->home();
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        Auth::logout();
        $this->noticeService->sendSuccessNotice('登出成功');
        return Inertia::location(Session::pull('url.intended', '/'));
    }
}
