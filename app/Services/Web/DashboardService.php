<?php

namespace App\Services\Web;

use App\Enums\Game\Log\Types;
use App\Exceptions\Web\Dashboard\SettingUpdateException;
use App\Models\Game\Account;
use App\Models\Game\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DashboardService
{
    public function __construct(
        public NotificationService $notification
    )
    {
    }

    /**
     * @param int|null $accountID
     * @return Account
     */
    public function getAccount(int $accountID = null): Account
    {
        if (!empty($accountID)) {
            $account = Account::findOrFail($accountID);
        } else {
            /** @var Account $account */
            $account = Auth::user();
        }

        return $account;
    }

    /**
     * @param int|null $accountID
     * @param array $load
     * @return Account
     */
    public function getAccountWithUser(int $accountID = null, array $load = []): Account
    {
        return $this->getAccount($accountID)->load(...$load + ['user']);
    }

    /**
     * @param int|null $accountID
     * @param bool $withUser
     * @param array $load
     */
    public function shareAccount(int $accountID = null, bool $withUser = true, array $load = [])
    {
        Inertia::share(
            'account',
            $withUser === true ? $this->getAccountWithUser($accountID, $load) : $this->getAccount($accountID)
        );
    }

    /**
     * @return bool
     */
    public function resendVerificationEmail(): bool
    {
        $account = $this->getAccount();
        if (!$account->hasVerifiedEmail()) {
            $account->sendEmailVerificationNotification();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function syncUserName(): bool
    {
        $account = $this->getAccountWithUser();
        $account->user->name = $account->name;
        $account->user->save();

        return true;
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @return bool
     * @throws SettingUpdateException
     */
    public function updateSetting(?string $name, ?string $email, ?string $password): bool
    {
        $account = $this->getAccount();

        if (empty($name) && empty($email) && empty($password)) {
            throw new SettingUpdateException('啥也没改...');
        } else {
            $log = Log::where([
                'type' => Types::UPDATE_PROFILE,
                'value' => "$name:$email",
                'user' => $account->user->id,
                'ip' => Request::ip()
            ])->first();

            if (!empty($log) && $log->created_at->diffInDays() < 7) {
                throw new SettingUpdateException('每7天只能改一次资料');
            } else {
                if (!empty($name) && $account->name !== $name) {
                    $account->name = $name;
                }

                if (!empty($email) && $account->email !== $email) {
                    $account->email = $email;
                    $account->sendEmailVerificationNotification();
                }

                if (!empty($password)) {
                    if (Hash::check($password, $account->password)) {
                        throw new SettingUpdateException('新密码不能和老密码一样');
                    } else {
                        $account->password = Hash::make($password);
                    }
                }

                $log = new Log();
                $log->type = Types::UPDATE_PROFILE;
                $log->value = "$name:$email";
                $log->user = $account->user->id;
                $log->ip = Request::ip();
                $log->save();

                $account->save();
                return true;
            }
        }
    }

    /**
     * @param string|null $email
     * @return bool
     */
    public function emailChanged(?string $email): bool
    {
        $account = $this->getAccount();
        return !empty($email) && $account->email !== $email;
    }
}
