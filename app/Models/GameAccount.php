<?php

namespace App\Models;

use App\Exceptions\Game\InvalidArgumentException;
use App\Game\AccountFriendRequestsManager;
use App\Game\AccountFriendsManager;
use App\Game\AccountMessagesManager;
use Database\Factories\GameAccountFactory;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory as HasFactoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable as NotifiableTrait;
use Illuminate\Support\Carbon;

/**
 * Class GameAccount
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|GameAccountComment[] $comments
 * @property-read int|null $comments_count
 * @property-read AccountFriendsManager $friend
 * @property-read AccountFriendRequestsManager $friend_request
 * @property-read GameAccountFriendRequest[]|Builder[]|Collection|\Illuminate\Support\Collection $friend_requests
 * @property-read Collection|GameAccount[] $friends
 * @property-read AccountMessagesManager $message
 * @property-read Collection|GameAccountMessage[] $messages
 * @property-read int|null $messages_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read GameAccountPermissionGroup|null $permission
 * @property-read Collection|GameAccountMessage[] $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read GameAccountSetting|null $setting
 * @property-read GameUser|null $user
 * @method static Builder|GameAccount newModelQuery()
 * @method static Builder|GameAccount newQuery()
 * @method static Builder|GameAccount query()
 * @method static Builder|GameAccount whereCreatedAt($value)
 * @method static Builder|GameAccount whereEmail($value)
 * @method static Builder|GameAccount whereEmailVerifiedAt($value)
 * @method static Builder|GameAccount whereId($value)
 * @method static Builder|GameAccount whereName($value)
 * @method static Builder|GameAccount wherePassword($value)
 * @method static Builder|GameAccount whereRememberToken($value)
 * @method static Builder|GameAccount whereUpdatedAt($value)
 * @mixin Model
 * @method static GameAccountFactory factory(...$parameters)
 */
class GameAccount extends Model implements MustVerifyEmailContract, CanResetPasswordContract, AuthenticatableContract, AuthorizableContract
{
    use HasFactoryTrait;
    use NotifiableTrait;
    use MustVerifyEmailTrait;
    use CanResetPasswordTrait;
    use AuthorizableTrait;
    use AuthenticatableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'password',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return AccountFriendsManager
     */
    public function getFriendAttribute(): AccountFriendsManager
    {
        return new AccountFriendsManager(
            $this,
            app(GameAccountFriend::class)
        );
    }

    /**
     * @return AccountMessagesManager
     */
    public function getMessageAttribute(): AccountMessagesManager
    {
        return new AccountMessagesManager($this);
    }

    /**
     * @return AccountFriendRequestsManager
     */
    public function getFriendRequestAttribute(): AccountFriendRequestsManager
    {
        return new AccountFriendRequestsManager($this);
    }

    /**
     * @return Collection|GameAccount[]
     */
    public function getFriendsAttribute()
    {
        $friends = GameAccountFriend::query()
            ->orWhere(['account' => $this->id, 'target_account' => $this->id])
            ->get(['account', 'target_account'])
            ->map(function (GameAccountFriend $friend) {
                return $friend->account === $this->id ? $friend->target_account : $friend->account;
            })->toArray();

        return self::query()->findMany($friends);
    }

    /**
     * @return GameAccountFriendRequest[]|Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function getFriendRequestsAttribute()
    {
        return GameAccountFriendRequest::query()
            ->orWhere([
                'account' => $this->id,
                'to_account' => $this->id
            ])->get();
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(GameUser::class, 'uuid');
    }

    /**
     * @return HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(GameAccountSetting::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(GameAccountComment::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(GameAccountMessage::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(GameAccountMessage::class, 'to_account');
    }

    /**
     * @return HasOneThrough
     */
    public function permission(): HasOneThrough
    {
        return $this->hasOneThrough(GameAccountPermissionGroup::class, GameAccountPermissionAssign::class, 'account', 'id', null, 'group');
    }

    /**
     * @param null $udid
     * @return GameUser|Builder|Model
     * @throws InvalidArgumentException
     */
    public function resolveUser($udid = null)
    {
        if (empty($udid)) {
            throw new InvalidArgumentException('Udid was empty');
        }

        return GameUser::query()
            ->firstOrCreate([
                'uuid' => $this->id
            ], [
                'name' => $this->name,
                'udid' => $udid
            ]);
    }

    /**
     * @param $name
     * @return bool
     */
    public function assignGroup($name): bool
    {
        try {
            $group = GameAccountPermissionGroup::whereName($name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $assign = GameAccountPermissionAssign::query()
            ->firstOrNew([
                'account' => $this->id
            ]);

        $assign->group = $group->id;
        return $assign->save();
    }
}
