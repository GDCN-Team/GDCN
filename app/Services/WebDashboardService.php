<?php

namespace App\Services;

use App\Exceptions\GameChangeEmailSameEmailException;
use App\Exceptions\GameChangeNameSameNameException;
use App\Exceptions\GameChangePasswordSamePasswordException;
use App\Models\GameAccount;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Class WebDashboardService
 * @package App\Services
 */
class WebDashboardService
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * @var GameAccountService
     */
    protected $gameAccountService;

    /**
     * WebDashboardService constructor.
     * @param WebNoticeService $noticeService
     * @param GameAccountService $gameAccountService
     */
    public function __construct(WebNoticeService $noticeService, GameAccountService $gameAccountService)
    {
        $this->noticeService = $noticeService;
        $this->gameAccountService = $gameAccountService;
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

        try {
            $this->gameAccountService->changeName($account, $name)
                ? $this->noticeService->sendSuccessNotice('用户名修改成功!')
                : $this->noticeService->sendErrorNotice('用户名修改失败');
        } catch (GameChangeNameSameNameException $e) {
            $this->noticeService->sendInfoNotice('用户名未作修改');
        }

        try {
            $this->gameAccountService->changeEmail($account, $email)
                ? $this->noticeService->sendSuccessNotice('邮箱修改成功!', '请去邮箱内重新验证您的账号')
                : $this->noticeService->sendErrorNotice('邮箱修改失败');
        } catch (GameChangeEmailSameEmailException $e) {
            $this->noticeService->sendInfoNotice('邮箱未作修改');
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

        try {
            $this->gameAccountService->changePassword($account, $newPassword)
                ? $this->noticeService->sendSuccessNotice('密码修改成功!')
                : $this->noticeService->sendErrorNotice('密码修改失败');
        } catch (GameChangePasswordSamePasswordException $e) {
            $this->noticeService->sendErrorNotice('您的新密码不能与老密码一样');
        }

        return Inertia::location(route('dashboard.home'));
    }
}
