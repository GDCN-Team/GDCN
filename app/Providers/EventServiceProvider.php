<?php

namespace App\Providers;

use App\Events\AccountCommentUploaded;
use App\Events\FriendAdded;
use App\Events\FriendRemoved;
use App\Events\LevelCommentUploaded;
use App\Events\LevelDeleted;
use App\Events\LevelUploaded;
use App\Listeners\ExecuteAccountCommentCommand;
use App\Listeners\ExecuteLevelCommentCommand;
use App\Listeners\LogFriendAdded;
use App\Listeners\LogFriendRemoved;
use App\Listeners\LogLevelDeleted;
use App\Listeners\LogLevelUploaded;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LevelUploaded::class => [
            LogLevelUploaded::class
        ],
        LevelDeleted::class => [
            LogLevelDeleted::class
        ],
        FriendAdded::class => [
            LogFriendAdded::class
        ],
        FriendRemoved::class => [
            LogFriendRemoved::class
        ],
        AccountCommentUploaded::class => [
            ExecuteAccountCommentCommand::class
        ],
        LevelCommentUploaded::class => [
            ExecuteLevelCommentCommand::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
