<?php

namespace App\Models\Game;

use App\Exceptions\Game\InvalidArgumentException;
use App\Game\AccountFriendRequestsManager;
use App\Game\AccountFriendsManager;
use App\Game\AccountMessagesManager;
use App\Models\Game\Account\Comment;
use App\Models\Game\Account\Friend;
use App\Models\Game\Account\FriendRequest;
use App\Models\Game\Account\Link;
use App\Models\Game\Account\Message;
use App\Models\Game\Account\Permission\Assign;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Account\Setting;
use Database\Factories\Game\AccountFactory;
use Eloquent;
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
 * Class Account
 *
 * @package App\Models\Game
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read AccountFriendsManager $friend
 * @property-read AccountFriendRequestsManager $friend_request
 * @property-read FriendRequest[]|Builder[]|Collection|\Illuminate\Support\Collection $friend_requests
 * @property-read Collection|Account[] $friends
 * @property-read AccountMessagesManager $message
 * @property-read Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Group|null $permission
 * @property-read Collection|Message[] $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read Setting|null $setting
 * @property-read User|null $user
 * @method static AccountFactory factory(...$parameters)
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static Builder|Account query()
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereEmail($value)
 * @method static Builder|Account whereEmailVerifiedAt($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereName($value)
 * @method static Builder|Account wherePassword($value)
 * @method static Builder|Account whereRememberToken($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Link[] $links
 * @property-read int|null $links_count
 */
class Account extends Model implements MustVerifyEmailContract, CanResetPasswordContract, AuthenticatableContract, AuthorizableContract
{
    use HasFactoryTrait;
    use NotifiableTrait;
    use MustVerifyEmailTrait;
    use CanResetPasswordTrait;
    use AuthorizableTrait;
    use AuthenticatableTrait;

    /**
     * @var string
     */
    protected $table = 'game_accounts';

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
     * @return Collection
     */
    public function getFriendsAttribute(): Collection
    {
        $friends = Friend::query()
            ->orWhere(['account' => $this->id, 'target_account' => $this->id])
            ->get(['account', 'target_account'])
            ->map(function (Friend $friend) {
                return $friend->account === $this->id ? $friend->target_account : $friend->account;
            })->toArray();

        return self::query()->findMany($friends);
    }

    public function permission_group(): HasOneThrough
    {
        return $this->hasOneThrough(Group::class, Assign::class, 'account', 'id', 'id', 'group');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'uuid');
    }

    /**
     * @return HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'account');
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_account');
    }

    /**
     * @return HasOneThrough
     */
    public function permission(): HasOneThrough
    {
        return $this->hasOneThrough(Group::class, Assign::class, 'account', 'id', null, 'group');
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'account');
    }

    /**
     * @param null $udid
     * @return User|Builder|Model
     * @throws InvalidArgumentException
     */
    public function resolveUser($udid = null)
    {
        if (empty($udid)) {
            throw new InvalidArgumentException('Udid was empty');
        }

        return User::query()
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
            $group = Group::whereName($name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $assign = Assign::query()
            ->firstOrNew([
                'account' => $this->id
            ]);

        $assign->group = $group->id;
        return $assign->save();
    }
}
