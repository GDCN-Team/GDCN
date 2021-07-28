<?php

namespace App\Console;

use App\Models\Game\Account;
use App\Models\Game\Account\PasswordReset;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = now();
            $hourLater = $now->addHour();
            Account::query()->whereNull('email_verified_at')->where('created_at', '<', $hourLater)->delete();
        })->name('Delete unverified account');

        $schedule->call(function () {
            $now = now();
            $expiredTime = $now->addSeconds(config('auth.password_timeout', 10800));
            PasswordReset::query()->where('created_at', '<', $expiredTime)->delete();
        })->name('Delete expired password reset data');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
