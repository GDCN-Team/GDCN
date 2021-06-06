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
     * @param $name
     * @param $password
     * @param $email
     * @return GameAccount
     */
    public function register($name, $password, $email): GameAccount
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
     * @param $newName
     * @return bool
     * @throws GameChangeNameSameNameException
     */
    public function changeName(GameAccount $account, $newName): bool
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
     * @param $newPassword
     * @return bool
     * @throws GameChangePasswordSamePasswordException
     */
    public function changePassword(GameAccount $account, $newPassword): bool
    {
        if (Hash::check($newPassword, $account->password)) {
            throw new GameChangePasswordSamePasswordException;
        }

        $account->password = Hash::make($newPassword);
        return $account->save();
    }

    /**
     * @param GameAccount $account
     * @param $newEmail
     * @return bool
     * @throws GameChangeEmailSameEmailException
     */
    public function changeEmail(GameAccount $account, $newEmail): bool
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
