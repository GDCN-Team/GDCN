<?php

namespace App\Providers;

use App\Models\Game\Account;
use App\Models\Game\User;
use GDCN\Hash\Hasher;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

/**
 * Class GameServiceProvider
 * @package App\Providers
 */
class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerNewEmailVerifyLinkGenerateMethod();
        $this->registerAuthDriver();
    }

    /**
     * @return void
     */
    public function registerNewEmailVerifyLinkGenerateMethod(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'game.account.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    '_' => Crypt::encryptString($notifiable->getKey() . ':' . $notifiable->getEmailForVerification()),
                ]
            );
        });
    }

    public function registerAuthDriver()
    {
        Auth::viaRequest('game', function (Request $request) {
            // By name and password
            if ($request->has(['userName', 'password'])) {
                $account = Account::whereName($request->get('userName'));
                if ($account && Hash::check($request->get('password'), $account->password)) {
                    return $account;
                }
            }

            // By accountID and gjp
            if ($request->has(['accountID', 'gjp'])) {
                $hasher = app(Hasher::class);
                $account = Account::whereId($request->get('accountID'));
                if ($account && Hash::check($hasher->decodeGJP($request->get('gjp')), $account->password)) {
                    return $account;
                }
            }

            // By uuid and udid
            if ($request->has(['uuid', 'udid'])) {
                $user = User::whereUdid($request->get('udid'));
                if ($user && $user->uuid === $request->get('udid') || $user->id === $request->get('uuid')) {
                    return $user;
                }
            }

            return null;
        });
    }
}
