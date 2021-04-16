<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\WebApiAccountPasswordUpdateRequest;
use App\Http\Requests\WebApiAccountSettingUpdateRequest;
use App\Http\Requests\WebApiLoginRequest;
use App\Http\Requests\WebApiRegisterRequest;
use App\Models\GameAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class WebApiController extends Controller
{
    /**
     * @param WebApiRegisterRequest $request
     * @return array
     */
    public function register(WebApiRegisterRequest $request): array
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $account = GameAccount::create($data);
        event(new Registered($account));
        Auth::login($account, true);

        $request->session()
            ->flash('notification', [
                'type' => 'success',
                'message' => "账号注册成功! 请去邮箱内验证您的账号"
            ]);

        return $request->success();
    }

    /**
     * @param WebApiLoginRequest $request
     * @return array
     */
    public function login(WebApiLoginRequest $request): array
    {
        return Auth::attempt($request->validated(), true) ? $request->success() : $request->failed();
    }

    /**
     * @param ApiRequest $request
     * @return array
     */
    public function logout(ApiRequest $request): array
    {
        Auth::logout();
        return $request->success();
    }

    /**
     * @param WebApiAccountSettingUpdateRequest $request
     * @return array
     */
    public function updateAccountSetting(WebApiAccountSettingUpdateRequest $request): array
    {
        $data = $request->validated();

        /** @var GameAccount $account */
        $account = Auth::user();
        if (!$account) {
            return $request->failed('账号不存在');
        }

        $account->name = $data['name'];
        $account->email = $data['email'];
        $account->save();

        event(new Registered($account));
        return $request->success();
    }

    /**
     * @param WebApiAccountPasswordUpdateRequest $request
     * @return array
     */
    public function updateAccountPassword(WebApiAccountPasswordUpdateRequest $request): array
    {
        $data = $request->validated();

        /** @var GameAccount $account */
        $account = Auth::user();
        if (!$account) {
            return $request->failed('账号不存在');
        }

        $account->password = Hash::make($data['password']);
        $account->save();
        return $request->success();
    }

    /**
     * @param ApiRequest $request
     * @return array
     */
    public function resendEmail(ApiRequest $request): array
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        if ($account->hasVerifiedEmail()) {
            return $request->failed('您已经验证过了，无需重复验证');
        }

        if (Cache::has('last_resend_verification_email_time')) {
            return $request->failed('重发请求过于频繁，请稍后再试');
        } else {
            Cache::put('last_resend_verification_email_time', now(), now()->addHour());
        }

        $account->sendEmailVerificationNotification();
        return $request->success();
    }
}
