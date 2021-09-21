<?php

namespace App\Console;

use App\Models\Game\Account;
use App\Models\Game\Account\PasswordReset;
use App\Services\Game\AntiCheatService;
use App\Services\Game\OptimizeService;
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
            Account::whereNull('email_verified_at')->whereTime('created_at', '<', $hourLater)->delete();
        })->daily()->name('删除一小时未验证邮箱的账号');

        $schedule->call(function () {
            $now = now();
            $expiredTime = $now->addSeconds(config('auth.password_timeout', 10800));
            PasswordReset::where('created_at', '<', $expiredTime)->delete();
        })->daily()->name('删除过期密码重置请求');

        $schedule->call(function () {
            app(AntiCheatService::class)->run();
        })->daily()->name('运行反作弊服务');

        $schedule->call(function () {
            app(OptimizeService::class)->run();
        })->daily()->name('运行优化服务');
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
