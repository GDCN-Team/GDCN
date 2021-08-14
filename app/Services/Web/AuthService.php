<?php

namespace App\Services\Web;

use App\Exceptions\Web\Auth\PasswordResetException;
use App\Models\Game\Account;
use App\Models\Game\Account\PasswordReset;
use App\Services\Game\AccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        public NotificationService $notification,
        public AccountService      $accountService
    )
    {
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @return Account
     */
    public function register(string $name, string $password, string $email): Account
    {
        return $this->accountService->register($name, $password, $email);
    }

    /**
     * @param string $name
     * @param string $password
     * @return bool
     */
    public function login(string $name, string $password): bool
    {
        $data = compact('name', 'password');
        return Auth::attempt($data, true);
    }

    /**
     * @return void
     */
    public function confirm_password()
    {
        Session::passwordConfirmed();
    }

    /**
     * @return void
     */
    public function logout()
    {
        Auth::logout();
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function loginUsingEmail(string $email, string $password): bool
    {
        $data = compact('email', 'password');
        return Auth::attempt($data, true);
    }

    /**
     * @param string $email
     * @return Account|null
     */
    public function getAccountUsingEmail(string $email): ?Account
    {
        return Account::whereEmail($email)->first();
    }

    /**
     * @param string $name
     * @param string $email
     * @return bool
     */
    public function sendResetPasswordEmail(string $name, string $email): bool
    {
        $account = Account::where([
            'name' => $name,
            'email' => $email
        ])->first();

        if (empty($account)) {
            return false;
        }

        $reset = new PasswordReset;
        $reset->account = $account->id;
        $reset->token = Str::random(32);
        $reset->save();

        $account->sendPasswordResetNotification($reset->token);
        return true;
    }

    /**
     * @param string $name
     * @param string $email
     * @return bool
     */
    public function checkEmailBelongsToAccountByName(string $name, string $email): bool
    {
        return Account::where([
            'name' => $name,
            'email' => $email
        ])->exists();
    }

    /**
     * @param int $accountID
     * @param string $token
     * @param string $email
     * @param string $password
     * @return bool
     * @throws PasswordResetException
     */
    public function resetPassword(int $accountID, string $token, string $email, string $password): bool
    {
        $account = Account::find($accountID);
        if (empty($account) || $account->email !== $email) {
            return false;
        }

        $reset = PasswordReset::whereToken($token)->first();
        if (empty($reset) || !$reset->acc->is($account)) {
            return false;
        }

        if (Hash::check($password, $account->password)) {
            throw new PasswordResetException('新密码不能与旧密码相同');
        }

        $account->password = Hash::make($password);
        $account->save();

        return $reset->delete();
    }
}
