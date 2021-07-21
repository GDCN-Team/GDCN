<?php

namespace App\Services\Web;

use App\Exceptions\Game\Account\Setting\EmailChangeException;
use App\Exceptions\Game\Account\Setting\NameChangeException;
use App\Exceptions\Game\Account\Setting\PasswordChangeException;
use App\Models\Game\Account;
use App\Presenter\Web\DashboardPresenter;
use App\Services\Game\AccountService;
use Illuminate\Support\Facades\Auth;
use Inertia\Response as InertiaResponse;

/**
 * Class DashboardService
 * @package App\Services
 */
class DashboardService
{
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
    protected $presenter;

    /**
     * DashboardService constructor.
     * @param DashboardPresenter $presenter
     * @param NoticeService $noticeService
     * @param AccountService $gameAccountService
     */
    public function __construct(DashboardPresenter $presenter, NoticeService $noticeService, AccountService $gameAccountService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
        $this->gameAccountService = $gameAccountService;
    }

    /**
     * @param string $name
     * @param string $email
     * @return InertiaResponse
     */
    public function updateAccountSetting(string $name, string $email): InertiaResponse
    {
        /** @var Account $account */
        $account = Auth::user();

        try {
            $this->gameAccountService->changeName($account, $name)
                ? $this->noticeService->sendSuccessNotice('用户名修改成功!')
                : $this->noticeService->sendErrorNotice('用户名修改失败');
        } catch (NameChangeException $e) {
            $this->noticeService->sendInfoNotice('用户名未作修改');
        }

        try {
            $this->gameAccountService->changeEmail($account, $email)
                ? $this->noticeService->sendSuccessNotice('邮箱修改成功!', '请去邮箱内重新验证您的账号')
                : $this->noticeService->sendErrorNotice('邮箱修改失败');
        } catch (EmailChangeException $e) {
            $this->noticeService->sendInfoNotice('邮箱未作修改');
        }

        return $this->presenter->profile();
    }

    /**
     * @param string $newPassword
     * @return InertiaResponse
     */
    public function changePassword(string $newPassword): InertiaResponse
    {
        /** @var Account $account */
        $account = Auth::user();

        try {
            $this->gameAccountService->changePassword($account, $newPassword)
                ? $this->noticeService->sendSuccessNotice('密码修改成功!')
                : $this->noticeService->sendErrorNotice('密码修改失败');
        } catch (PasswordChangeException $e) {
            $this->noticeService->sendErrorNotice('您的新密码不能与老密码一样');
        }

        return $this->presenter->home();
    }
}
