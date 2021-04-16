<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelRatingSuggestion
 *
 * @package App\Models
 * @property int $id
 * @property int $user
 * @property int $level
 * @property int $type
 * @property int $rating
 * @property int $featured
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameLevelRatingSuggestion newModelQuery()
 * @method static Builder|GameLevelRatingSuggestion newQuery()
 * @method static Builder|GameLevelRatingSuggestion query()
 * @method static Builder|GameLevelRatingSuggestion whereUser($value)
 * @method static Builder|GameLevelRatingSuggestion whereCreatedAt($value)
 * @method static Builder|GameLevelRatingSuggestion whereFeatured($value)
 * @method static Builder|GameLevelRatingSuggestion whereId($value)
 * @method static Builder|GameLevelRatingSuggestion whereLevel($value)
 * @method static Builder|GameLevelRatingSuggestion whereRating($value)
 * @method static Builder|GameLevelRatingSuggestion whereType($value)
 * @method static Builder|GameLevelRatingSuggestion whereUpdatedAt($value)
 * @mixin Model
 */
class GameLevelRatingSuggestion extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user',
        'level',
        'type',
        'rating',
        'featured'
    ];
}
