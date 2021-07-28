<?php

namespace App\Http\Controllers\Web;

use App\Enums\Game\Log\Types;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Traits\AuthTrait;
use App\Http\Controllers\Web\Traits\ResponseTrait;
use App\Http\Requests\Web\Api\Dashboard\SettingsUpdateRequest;
use App\Models\Game\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    use AuthTrait;
    use ResponseTrait;

    /**
     * @return array
     */
    public function getPlayerProfile(): array
    {
        $account = $this->getAccount();
        return $this->response(true, null, [
            'account' => $account,
            'user' => $account->user
        ]);
    }

    /**
     * @param SettingsUpdateRequest $request
     * @return array
     */
    public function updateSettings(SettingsUpdateRequest $request): array
    {
        $account = $this->getAccount();
        $data = $request->validated();

        if ($account->name === $data['name']
            && $account->email === $data['email']) {
            return $this->response(false, '你啥也没改呀!');
        }

        $log = Log::firstOrNew([
            'user' => $account->user->id,
            'type' => Types::UPDATE_PROFILE
        ], [
            'value' => implode(',', Arr::except($data, 'password')),
            'ip' => $request->ip()
        ]);

        if ($log->exists()) {
            return $this->response(false, '技能冷却中...');
        } else {
            $log->save();
        }

        $account->name = $data['name'];
        if (!empty($data['password'])) {
            $account->password = Hash::make($data['password']);
        }

        if ($account->email !== $data['email']) {
            $account->email = $data['email'];
            $account->email_verified_at = null;
            $account->sendEmailVerificationNotification();
        }

        $result = $account->save();
        return $this->response($result);
    }

    /**
     * @return array
     */
    public function resendEmailVerification(): array
    {
        $account = $this->getAccount();

        if ($account->hasVerifiedEmail()) {
            return $this->response(false, '账号已通过邮箱验证');
        }

        $account->sendEmailVerificationNotification();
        return $this->response(true, '邮件发送成功!');
    }
}
