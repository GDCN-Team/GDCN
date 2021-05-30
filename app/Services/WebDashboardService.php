<?php

namespace App\Services;

use App\Models\GameAccount;
use App\Presenter\WebAuthPresenter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

/**
 * Class WebDashboardService
 * @package App\Services
 */
class WebDashboardService
{
    /**
     * @var WebAuthPresenter
     */
    protected $authPresenter;

    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebDashboardService constructor.
     * @param WebAuthPresenter $authPresenter
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebAuthPresenter $authPresenter, WebNoticeService $noticeService)
    {
        $this->authPresenter = $authPresenter;
        $this->noticeService = $noticeService;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Response
     */
    public function updateAccountSetting(string $name, string $email): Response
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        if ($account->name !== $name) {
            if ($user = $account->user) {
                $user->name = $name;
                $user->save();
            }

            $account->name = $name;
            $account->save();

            $this->noticeService->sendSuccessNotice('用户名修改成功!');
        }

        if ($account->email !== $email) {
            $account->email = $email;
            $account->email_verified_at = null;
            $account->save();

            $this->noticeService->sendSuccessNotice('邮箱修改成功!', '请去邮箱内重新验证您的账号');
            event(new Registered($account));
        }

        return Inertia::location(route('dashboard.profile'));
    }

    /**
     * @param string $newPassword
     * @return Response
     */
    public function changePassword(string $newPassword): Response
    {
        /** @var GameAccount $account */
        $account = Auth::user();
        $account->password = Hash::make($newPassword);
        $account->save();

        $this->noticeService->sendSuccessNotice('密码修改成功!');
        return Inertia::location(route('dashboard.home'));
    }
}
