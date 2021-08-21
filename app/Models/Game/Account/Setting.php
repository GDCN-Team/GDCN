<?php

namespace App\Models\Game\Account;

use App\Enums\Game\Account\Setting\CommentHistoryState;
use App\Enums\Game\Account\Setting\FriendRequestState;
use App\Enums\Game\Account\Setting\MessageState;
use App\Models\Game\Account;
use Database\Factories\Game\Account\SettingFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Setting
 *
 * @property int $id
 * @property Account $account
 * @property MessageState $message_state
 * @property FriendRequestState $friend_request_state
 * @property CommentHistoryState $comment_history_state
 * @property string|null $youtube
 * @property string|null $twitter
 * @property string|null $twitch
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static SettingFactory factory(...$parameters)
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereAccount($value)
 * @method static Builder|Setting whereCommentHistoryState($value)
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereFriendRequestState($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereMessageState($value)
 * @method static Builder|Setting whereTwitch($value)
 * @method static Builder|Setting whereTwitter($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereYoutube($value)
 * @mixin Eloquent
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'game_account_settings';

    protected $casts = [
        'message_state' => MessageState::class,
        'friend_request_state' => FriendRequestState::class,
        'comment_history_state' => CommentHistoryState::class
    ];

    protected $fillable = [
        'message_state',
        'friend_request_state',
        'comment_history_state',
        'youtube',
        'twitter',
        'twitch'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }
}
