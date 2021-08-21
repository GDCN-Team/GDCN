<?php

namespace App\Models\Game;

use App\Models\Game\Account\Block;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Account\Friend;
use App\Models\Game\Account\FriendRequest;
use App\Models\Game\Account\Link;
use App\Models\Game\Account\Message;
use App\Models\Game\Account\PasswordReset;
use App\Models\Game\Account\Permission\Assign;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Account\Setting;
use App\Models\Game\Level\Comment as LevelComment;
use Database\Factories\Game\AccountFactory;
use Eloquent;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory as HasFactoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable as NotifiableTrait;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Block[] $blocks
 * @property-read int|null $blocks_count
 * @property-read Collection|AccountComment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|FriendRequest[] $friend_requests
 * @property-read int|null $friend_requests_count
 * @property-read Collection|Friend[] $friends
 * @property-read int|null $friends_count
 * @property-read Collection|LevelComment[] $level_comments
 * @property-read int|null $level_comments_count
 * @property-read Collection|Link[] $links
 * @property-read int|null $links_count
 * @property-read Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|PasswordReset[] $password_resets
 * @property-read int|null $password_resets_count
 * @property-read Group|null $permission_group
 * @property-read Collection|FriendRequest[] $sent_friend_requests
 * @property-read int|null $sent_friend_requests_count
 * @property-read Collection|Message[] $sent_messages
 * @property-read int|null $sent_messages_count
 * @property-read Setting|null $setting
 * @property-read User|null $user
 * @method static AccountFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Account extends Model implements MustVerifyEmailContract, CanResetPasswordContract, AuthenticatableContract, AuthorizableContract
{
    use HasFactoryTrait, NotifiableTrait, MustVerifyEmailTrait, CanResetPasswordTrait, AuthorizableTrait, AuthenticatableTrait;

    protected $table = 'game_accounts';

    protected $casts = ['email_verified_at' => 'datetime'];

    protected $hidden = ['email', 'remember_token'];

    protected $fillable = ['name', 'email', 'password'];

    protected $dispatchesEvents = ['created' => Registered::class];

    public function permission_group(): HasOneThrough
    {
        return $this->hasOneThrough(Group::class, Assign::class, 'account', 'id', 'id', 'group');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'account');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AccountComment::class, 'account');
    }

    public function friends(): HasMany
    {
        /** @var Builder $query */
        $query = $this->hasMany(Friend::class, 'target_account');

        return $this->hasMany(Friend::class, 'account')->union($query);
    }

    public function sent_friend_requests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'account');
    }

    public function friend_requests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'to_account');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'account');
    }

    public function sent_messages(): HasMany
    {
        return $this->hasMany(Message::class, 'account');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_account');
    }

    public function password_resets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'account');
    }

    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class, 'account');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'uuid');
    }

    public function level_comments(): HasMany
    {
        return $this->hasMany(LevelComment::class, 'account');
    }
}
