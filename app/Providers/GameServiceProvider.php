<?php

namespace App\Providers;

use App\Game\StorageManager;
use App\Http\Controllers\GameAccountSaveDataController;
use App\Http\Controllers\GameLevelsController;
use App\Http\Controllers\WebToolsApiController;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
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
                'game.verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        $this->app->when(GameAccountSaveDataController::class)
            ->needs(StorageManager::class)
            ->give(function() {
                return new StorageManager(config('game.storage.saveData'));
            });

        $this->app->when(GameLevelsController::class)
            ->needs(StorageManager::class)
            ->give(function() {
                return new StorageManager(config('game.storage.levels'));
            });
    }
}
