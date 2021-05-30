<?php

namespace App\Services;

use App\Models\GameAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

/**
 * Class WebAuthService
 * @package App\Services
 */
class WebAuthService
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebAuthService constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param string $name
     * @param string $password
     * @return bool
     */
    public function login(string $name, string $password): bool
    {
        return Auth::attempt(compact('name', 'password'));
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @return Response
     */
    public function register(string $name, string $password, string $email): Response
    {
        $account = new GameAccount;
        $account->name = $name;
        $account->password = Hash::make($password);
        $account->email = $email;
        $account->save();

        event(new Registered($account));
        Auth::login($account, true);

        $this->noticeService->sendSuccessNotice('注册成功!');
        return Inertia::location(route('dashboard.home'));
    }
}
