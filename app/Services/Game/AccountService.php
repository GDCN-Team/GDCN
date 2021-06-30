<?php

namespace App\Services\Game;

use App\Exceptions\Game\Account\Setting\EmailChangeException;
use App\Exceptions\Game\Account\Setting\NameChangeException;
use App\Exceptions\Game\Account\Setting\PasswordChangeException;
use App\Models\GameAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use function event;

/**
 * Class AccountService
 * @package App\Services
 */
class AccountService
{
    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @return GameAccount
     */
    public function register(string $name, string $password, string $email): GameAccount
    {
        $account = new GameAccount;
        $account->name = $name;
        $account->password = Hash::make($password);
        $account->email = $email;
        $account->save();

        event(new Registered($account));
        return $account;
    }

    /**
     * @param GameAccount $account
     * @param string $newName
     * @return bool
     * @throws NameChangeException
     */
    public function changeName(GameAccount $account, string $newName): bool
    {
        if ($account->name === $newName) {
            throw new NameChangeException;
        }

        if ($user = $account->user) {
            $user->name = $newName;
            $user->save();
        }

        $account->name = $newName;
        return $account->save();
    }

    /**
     * @param GameAccount $account
     * @param string $newPassword
     * @return bool
     * @throws \App\Exceptions\Game\Account\Setting\PasswordChangeException
     */
    public function changePassword(GameAccount $account, string $newPassword): bool
    {
        if (Hash::check($newPassword, $account->password)) {
            throw new PasswordChangeException;
        }

        $account->password = Hash::make($newPassword);
        return $account->save();
    }

    /**
     * @param GameAccount $account
     * @param string $newEmail
     * @return bool
     * @throws \App\Exceptions\Game\Account\Setting\EmailChangeException
     */
    public function changeEmail(GameAccount $account, string $newEmail): bool
    {
        if ($account->email === $newEmail) {
            throw new EmailChangeException;
        }

        $account->email = $newEmail;
        $account->email_verified_at = null;
        event(new Registered($account));

        return $account->save();
    }
}
