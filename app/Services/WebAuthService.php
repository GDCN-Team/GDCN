<?php

namespace App\Services;

use App\Presenter\WebAuthPresenter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Class WebAuthService
 * @package App\Services
 */
class WebAuthService
{
    /**
     * @var WebAuthPresenter
     */
    protected $presenter;

    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * @var GameAccountService
     */
    protected $gameAccountService;

    /**
     * WebAuthService constructor.
     * @param WebAuthPresenter $presenter
     * @param WebNoticeService $noticeService
     * @param GameAccountService $gameAccountService
     */
    public function __construct(WebAuthPresenter $presenter, WebNoticeService $noticeService, GameAccountService $gameAccountService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
        $this->gameAccountService = $gameAccountService;
    }

    /**
     * @param string $name
     * @param string $password
     * @return Response|InertiaResponse
     */
    public function login(string $name, string $password)
    {
        $result = Auth::attempt(compact('name', 'password'));
        return $result ? Inertia::location(Session::pull('url.intended', '/')) : $this->presenter->login(['errors' => ['password' => '密码错误']]);
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @return Response
     */
    public function register(string $name, string $password, string $email): Response
    {
        $account = $this->gameAccountService->register($name, $password, $email);
        Auth::login($account, true);

        $this->noticeService->sendSuccessNotice('注册成功!');
        return Inertia::location(route('dashboard.home'));
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
