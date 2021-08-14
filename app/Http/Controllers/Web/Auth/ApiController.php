<?php

namespace App\Http\Controllers\Web\Auth;

use App\Exceptions\Web\Auth\PasswordResetException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginApiRequest;
use App\Http\Requests\Web\Auth\NameForgotRequest;
use App\Http\Requests\Web\Auth\PasswordConfirmApiRequest;
use App\Http\Requests\Web\Auth\PasswordForgotRequest;
use App\Http\Requests\Web\Auth\PasswordResetRequest;
use App\Http\Requests\Web\Auth\RegisterApiRequest;
use App\Services\Web\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;

class ApiController extends Controller
{
    /**
     * @param AuthService $service
     */
    public function __construct(
        public AuthService $service
    )
    {
    }

    /**
     * @param RegisterApiRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterApiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $account = $this->service->register($data['name'], $data['password'], $data['email']);

        Auth::login($account, true);
        $this->service->notification->sendMessage('success', '注册成功! 请验证您的邮箱');

        return Redirect::route('dashboard.profile');
    }

    /**
     * @param LoginApiRequest $request
     * @return RedirectResponse|Response
     */
    public function login(LoginApiRequest $request): Response|RedirectResponse
    {
        $data = $request->validated();
        if (!$this->service->login($data['name'], $data['password'])) {
            $this->service->notification->sendMessage('error', '登录失败');
            return back();
        }

        return Redirect::route('dashboard.profile');
    }

    /**
     * @param PasswordConfirmApiRequest $request
     * @return RedirectResponse
     */
    public function passwordConfirm(PasswordConfirmApiRequest $request): RedirectResponse
    {
        $request->validated();
        $this->service->confirm_password();

        return back();
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->service->logout();
        return Redirect::route('auth.login');
    }

    /**
     * @param PasswordForgotRequest $request
     * @return Response|RedirectResponse
     */
    public function forgotPassword(PasswordForgotRequest $request): Response|RedirectResponse
    {
        $data = $request->validated();
        if (!$this->service->checkEmailBelongsToAccountByName($data['name'], $data['email'])) {
            $this->service->notification->sendMessage('error', '发送失败, 用户名与邮箱不匹配');
            return back();
        }

        if (!$this->service->sendResetPasswordEmail($data['name'], $data['email'])) {
            $this->service->notification->sendMessage('error', '发送失败');
            return back();
        }

        $this->service->notification->sendMessage('success', '密码重置邮件发送成功!');
        return Redirect::route('home');
    }

    /**
     * @param NameForgotRequest $request
     * @return Response|RedirectResponse
     */
    public function forgotName(NameForgotRequest $request): Response|RedirectResponse
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            if (!$this->service->loginUsingEmail($data['email'], $data['password'])) {
                $this->service->notification->sendMessage('error', '登录失败');
                return back();
            }

            return Redirect::route('dashboard.profile');
        } else {
            $account = $this->service->getAccountUsingEmail($data['email']);
            if (!$account) {
                $this->service->notification->sendMessage('error', '账号不存在(或未找到)');
                return back();
            }

            $this->service->notification->sendMessage('success', "找到账号: $account->name");
            return Redirect::route('dashboard.account.info', $account->id);
        }
    }

    /**
     * @param PasswordResetRequest $request
     * @return Response|RedirectResponse
     */
    public function resetPassword(PasswordResetRequest $request): Response|RedirectResponse
    {
        $data = $request->validated();
        $string = Crypt::decryptString($data['_']);
        [$accountID, $token, $email] = explode(':', $string);

        if (empty($accountID) || $accountID <= 0) {
            $this->service->notification->sendMessage('error', '参数错误');
            return back();
        }

        try {
            if (!$this->service->resetPassword($accountID, $token, $email, $data['password'])) {
                $this->service->notification->sendMessage('error', '重置失败');
                return back();
            }

            $this->service->notification->sendMessage('success', '密码重置完成!');
            return Redirect::route('auth.login');
        } catch (PasswordResetException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }
}
