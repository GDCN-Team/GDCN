<?php

namespace App\Models\Game\Level;

use App\Models\Game\Account;
use Database\Factories\Game\Level\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Comment
 *
 * @package App\Models
 * @property int $id
 * @property int $level
 * @property int $account
 * @property string $content
 * @property int|null $percent
 * @property int $likes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account $sender
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAccount($value)
 * @method static Builder|Comment whereContent($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereLevel($value)
 * @method static Builder|Comment whereLikes($value)
 * @method static Builder|Comment wherePercent($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @mixin Model
 * @method static CommentFactory factory(...$parameters)
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_comments';

    /**
     * @return CommentFactory
     */
    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }
}
