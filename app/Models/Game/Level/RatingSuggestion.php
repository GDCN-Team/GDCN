<?php

namespace App\Models\Game\Level;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class RatingSuggestion
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $user
 * @property int $level
 * @property int $type
 * @property int $rating
 * @property int $featured
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RatingSuggestion newModelQuery()
 * @method static Builder|RatingSuggestion newQuery()
 * @method static Builder|RatingSuggestion query()
 * @method static Builder|RatingSuggestion whereCreatedAt($value)
 * @method static Builder|RatingSuggestion whereFeatured($value)
 * @method static Builder|RatingSuggestion whereId($value)
 * @method static Builder|RatingSuggestion whereLevel($value)
 * @method static Builder|RatingSuggestion whereRating($value)
 * @method static Builder|RatingSuggestion whereType($value)
 * @method static Builder|RatingSuggestion whereUpdatedAt($value)
 * @method static Builder|RatingSuggestion whereUser($value)
 * @mixin Eloquent
 */
class RatingSuggestion extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_rating_suggestions';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'user',
        'level',
        'rating',
        'featured'
    ];
}
