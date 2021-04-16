<?php

namespace App\Models;

use App\Enums\GameAccountSettingCommentHistoryStateType;
use App\Enums\GameAccountSettingFriendRequestStateType;
use App\Enums\GameAccountSettingMessageStateType;
use App\Models\GameAccount as AccountModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


/**
 * Class GameAccountSetting
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property GameAccountSettingMessageStateType $message_state
 * @property GameAccountSettingFriendRequestStateType $friend_request_state
 * @property GameAccountSettingCommentHistoryStateType $comment_history_state
 * @property string|null $youtube
 * @property string|null $twitter
 * @property string|null $twitch
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AccountModel|null $owner
 * @method static Builder|GameAccountSetting newModelQuery()
 * @method static Builder|GameAccountSetting newQuery()
 * @method static Builder|GameAccountSetting query()
 * @method static Builder|GameAccountSetting whereAccount($value)
 * @method static Builder|GameAccountSetting whereCommentHistoryState($value)
 * @method static Builder|GameAccountSetting whereCreatedAt($value)
 * @method static Builder|GameAccountSetting whereFriendRequestState($value)
 * @method static Builder|GameAccountSetting whereId($value)
 * @method static Builder|GameAccountSetting whereMessageState($value)
 * @method static Builder|GameAccountSetting whereTwitch($value)
 * @method static Builder|GameAccountSetting whereTwitter($value)
 * @method static Builder|GameAccountSetting whereUpdatedAt($value)
 * @method static Builder|GameAccountSetting whereYoutube($value)
 * @mixin Model
 */
class GameAccountSetting extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_settings';

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'message_state',
        'friend_request_state',
        'comment_history_state',
        'youtube',
        'twitter',
        'twitch'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'message_state' => GameAccountSettingMessageStateType::class,
        'friend_request_state' => GameAccountSettingFriendRequestStateType::class,
        'comment_history_state' => GameAccountSettingCommentHistoryStateType::class
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(AccountModel::class, 'id', 'account');
    }
}
