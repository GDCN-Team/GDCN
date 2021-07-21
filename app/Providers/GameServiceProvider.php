<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
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
}
