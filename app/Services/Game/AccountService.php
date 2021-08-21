<?php

namespace App\Services\Game;

use App\Models\Game\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AccountService
{
    public function register(string $name, string $password, string $email): Account
    {
        return Account::create([
            'name' => $name,
            'password' => Hash::make($password),
            'email' => $email
        ]);
    }

    public function verify(int $accountID, string $email): RedirectResponse
    {
        $account = Account::where([
            'id' => $accountID,
            'email' => $email
        ])->firstOrFail();

        $account->markEmailAsVerified();
        Auth::login($account, true);

        return Redirect::route('dashboard.home');
    }

    public function login(string $name, string $password, string $udid): bool
    {
        $account = Account::whereName($name)
            ->firstOrFail();

        $account->user()
            ->updateOrCreate([
                'uuid' => $account->id
            ], [
                'name' => $name,
                'udid' => $udid
            ]);

        return $account->update([
            'password' => Hash::make($password)
        ]);
    }
}
