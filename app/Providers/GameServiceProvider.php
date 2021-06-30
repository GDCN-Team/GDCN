<?php

namespace App\Providers;

use App\Game\StorageManager;
use App\Http\Controllers\Game\AccountSaveDataController;
use App\Http\Controllers\Game\LevelsController;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Paginator::defaultSimpleView('pagination::simple-bootstrap-4');
        Paginator::defaultView('pagination::bootstrap-4');

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'game.account.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    '_' => Crypt::encryptString($notifiable->getKey() . ":" . $notifiable->getEmailForVerification()),
                ]
            );
        });

        $this->app->when(AccountSaveDataController::class)
            ->needs(StorageManager::class)
            ->give(function() {
                return new StorageManager(config('game.storage.saveData'));
            });

        $this->app->when(LevelsController::class)
            ->needs(StorageManager::class)
            ->give(function() {
                return new StorageManager(config('game.storage.levels'));
            });
    }
}
