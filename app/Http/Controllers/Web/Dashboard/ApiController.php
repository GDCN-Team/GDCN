<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Exceptions\Web\Dashboard\SettingUpdateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\Setting\UpdateRequest;
use App\Services\Web\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ApiController extends Controller
{
    public function __construct(
        public DashboardService $service
    )
    {
    }

    /**
     * @return RedirectResponse
     */
    public function resendVerificationEmail(): RedirectResponse
    {
        if (!$this->service->resendVerificationEmail()) {
            $this->service->notification->sendMessage('error', '您的账号已经验证过了, 无需重复验证');
        } else {
            $this->service->notification->sendMessage('success', '邮件已发送! 请去邮箱内检查你的邮件');
        }

        return back();
    }

    /**
     * @return RedirectResponse
     */
    public function syncUserName(): RedirectResponse
    {
        $this->service->syncUserName();
        $this->service->notification->sendMessage('success', '同步完成!');
        return back();
    }

    /**
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function updateSetting(UpdateRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$this->service->updateSetting($data['name'], $data['email'], $data['password'])) {
                $this->service->notification->sendMessage('error', '修改失败');
                return back();
            } else {
                $this->service->notification->sendMessage('success', '修改成功!');

                if ($this->service->emailChanged($data['email'])) {
                    $this->service->notification->sendMessage('info', '邮箱已修改, 请去邮箱内重新验证您的账号!');
                }
            }

            return Redirect::route('dashboard.profile');
        } catch (SettingUpdateException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }
}
