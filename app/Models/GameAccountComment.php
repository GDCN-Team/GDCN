<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class GameAccountComment
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property string $content
 * @property int $likes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read GameAccount|null $sender
 * @method static Builder|GameAccountComment newModelQuery()
 * @method static Builder|GameAccountComment newQuery()
 * @method static Builder|GameAccountComment query()
 * @method static Builder|GameAccountComment whereAccount($value)
 * @method static Builder|GameAccountComment whereContent($value)
 * @method static Builder|GameAccountComment whereCreatedAt($value)
 * @method static Builder|GameAccountComment whereId($value)
 * @method static Builder|GameAccountComment whereLikes($value)
 * @method static Builder|GameAccountComment whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountComment extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'content'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'account' => 'integer'
    ];

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(GameAccount::class, 'account');
    }
}
