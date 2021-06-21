<?php

namespace App\Services;

use App\Exceptions\GameChangeEmailSameEmailException;
use App\Exceptions\GameChangeNameSameNameException;
use App\Exceptions\GameChangePasswordSamePasswordException;
use App\Models\GameAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

/**
 * Class GameAccountService
 * @package App\Services
 */
class GameAccountService
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
     * @throws GameChangeNameSameNameException
     */
    public function changeName(GameAccount $account, string $newName): bool
    {
        if ($account->name === $newName) {
            throw new GameChangeNameSameNameException;
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
     * @throws GameChangePasswordSamePasswordException
     */
    public function changePassword(GameAccount $account, string $newPassword): bool
    {
        if (Hash::check($newPassword, $account->password)) {
            throw new GameChangePasswordSamePasswordException;
        }

        $account->password = Hash::make($newPassword);
        return $account->save();
    }

    /**
     * @param GameAccount $account
     * @param string $newEmail
     * @return bool
     * @throws GameChangeEmailSameEmailException
     */
    public function changeEmail(GameAccount $account, string $newEmail): bool
    {
        if ($account->email === $newEmail) {
            throw new GameChangeEmailSameEmailException;
        }

        $account->email = $newEmail;
        $account->email_verified_at = null;
        event(new Registered($account));

        return $account->save();
    }
}
