<?php

namespace App\Providers;

use GDCN\Hash\Components\PageInfo;
use Illuminate\Auth\Notifications\ResetPassword;
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
        PageInfo::$per_page = config('game.perPage');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerNewEmailVerifyLinkGenerateMethod();
        $this->registerNewPasswordResetLinkGenerateMethod();
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

    public function registerNewPasswordResetLinkGenerateMethod(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return URL::temporarySignedRoute(
                'auth.password.reset',
                Carbon::now()->addMinutes(Config::get('auth.password_timeout', 10800)),
                [
                    '_' => Crypt::encryptString($notifiable->getKey() . ':' . $token . ':' . $notifiable->getEmailForVerification()),
                ]
            );
        });
    }
}
