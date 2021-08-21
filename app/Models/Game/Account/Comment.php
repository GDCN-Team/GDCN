<?php

namespace App\Models\Game\Account;

use App\Casts\Base64UrlCast;
use App\Models\Game\Account;
use Database\Factories\Game\Account\CommentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Comment
 *
 * @property int $id
 * @property Account $account
 * @property string $content
 * @property int $likes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static CommentFactory factory(...$parameters)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAccount($value)
 * @method static Builder|Comment whereContent($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereLikes($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'game_account_comments';

    protected $casts = [
        'content' => Base64UrlCast::class
    ];

    protected $fillable = ['content'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }
}
