<?php

namespace App\Models\Game\Account;

use App\Casts\Base64UrlCast;
use App\Models\Game\Account;
use Database\Factories\Game\Account\FriendRequestFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\FriendRequest
 *
 * @property int $id
 * @property Account $account
 * @property Account $to_account
 * @property string|null $comment
 * @property int $new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FriendRequestFactory factory(...$parameters)
 * @method static Builder|FriendRequest newModelQuery()
 * @method static Builder|FriendRequest newQuery()
 * @method static Builder|FriendRequest query()
 * @method static Builder|FriendRequest whereAccount($value)
 * @method static Builder|FriendRequest whereComment($value)
 * @method static Builder|FriendRequest whereCreatedAt($value)
 * @method static Builder|FriendRequest whereId($value)
 * @method static Builder|FriendRequest whereNew($value)
 * @method static Builder|FriendRequest whereToAccount($value)
 * @method static Builder|FriendRequest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'game_account_friend_requests';

    protected $casts = [
        'comment' => Base64UrlCast::class
    ];

    protected $fillable = ['to_account', 'comment'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function to_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account');
    }
}
