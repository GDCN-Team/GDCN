<?php

namespace App\Services\Game;

use App\Models\Game\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AccountService
{
    public function register(string $name, string $password, string $email): Account
    {
        Log::channel('gdcn')
            ->info('[Account System] Action: Register', [
                'name' => $name,
                'password' => $password,
                'email' => $email
            ]);

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
        ])->first();

        $account->markEmailAsVerified();
        Auth::login($account, true);

        Log::channel('gdcn')
            ->info('[Account System] Action: Verify Email', [
                'accountID' => $accountID,
                'email' => $email
            ]);

        return Redirect::route('dashboard.home');
    }

    public function login(string $name, string $password, string $udid): bool
    {
        $account = Account::whereName($name)
            ->first();

        $account->user()
            ->updateOrCreate([
                'uuid' => $account->id
            ], [
                'name' => $name,
                'udid' => $udid
            ]);

        Log::channel('gdcn')
            ->info('[Account System] Action: Login', [
                'name' => $name,
                'password' => $password,
                'udid' => str_repeat('*', strlen($udid))
            ]);


        return $account->update([
            'password' => Hash::make($password)
        ]);
    }
}
