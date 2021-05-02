<?php

namespace App\Models;

use App\Models\GameAccount as AccountModel;
use Database\Factories\GameLevelCommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelComment
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
 * @property-read AccountModel $sender
 * @method static Builder|GameLevelComment newModelQuery()
 * @method static Builder|GameLevelComment newQuery()
 * @method static Builder|GameLevelComment query()
 * @method static Builder|GameLevelComment whereAccount($value)
 * @method static Builder|GameLevelComment whereContent($value)
 * @method static Builder|GameLevelComment whereCreatedAt($value)
 * @method static Builder|GameLevelComment whereId($value)
 * @method static Builder|GameLevelComment whereLevel($value)
 * @method static Builder|GameLevelComment whereLikes($value)
 * @method static Builder|GameLevelComment wherePercent($value)
 * @method static Builder|GameLevelComment whereUpdatedAt($value)
 * @mixin Model
 * @method static GameLevelCommentFactory factory(...$parameters)
 */
class GameLevelComment extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'level',
        'account',
        'content'
    ];

    /**
     * @return GameLevelCommentFactory
     */
    protected static function newFactory(): GameLevelCommentFactory
    {
        return GameLevelCommentFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(AccountModel::class, 'account');
    }
}
