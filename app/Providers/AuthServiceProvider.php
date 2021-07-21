<?php

namespace App\Providers;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Account\FriendRequest;
use App\Models\Game\Account\Message;
use App\Models\Game\CustomSong;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Policies\Game\Account\CommentPolicy as AccountCommentPolicy;
use App\Policies\Game\Account\FriendRequestPolicy;
use App\Policies\Game\Account\MessagePolicy;
use App\Policies\Game\AccountPolicy;
use App\Policies\Game\CustomSongPolicy;
use App\Policies\Game\LevelCommentPolicy as LevelCommentPolicy;
use App\Policies\Game\LevelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        FriendRequest::class => FriendRequestPolicy::class,
        AccountComment::class => AccountCommentPolicy::class,
        Message::class => MessagePolicy::class,
        LevelComment::class => LevelCommentPolicy::class,
        Account::class => AccountPolicy::class,
        CustomSong::class => CustomSongPolicy::class,
        Level::class => LevelPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
