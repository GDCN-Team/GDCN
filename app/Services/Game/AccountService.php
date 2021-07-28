<?php

namespace App\Services\Game;

use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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
     * @return Account
     */
    public function register(string $name, string $password, string $email): Account
    {
        $account = new Account();
        $account->name = $name;
        $account->password = Hash::make($password);
        $account->email = $email;
        $account->save();

        event(new Registered($account));
        return $account;
    }

    /**
     * @param int $accountID
     * @param string $email
     * @return RedirectResponse
     */
    public function verify(int $accountID, string $email): RedirectResponse
    {
        $account = Account::where([
            'id' => $accountID,
            'email' => $email
        ])->first();

        if (!$account) {
            abort(404);
        }

        $account->markEmailAsVerified();
        Auth::login($account, true);

        return Redirect::route('dashboard.home');
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $udid
     * @return bool
     */
    public function login(string $name, string $password, string $udid): bool
    {
        if (Auth::once(compact('name', 'password'))) {
            try {
                /** @var Account $account */
                $account = Auth::user();
                $account->resolveUser($udid);
                return true;
            } catch (InvalidArgumentException) {
                return false;
            }
        }

        return false;
    }
}
