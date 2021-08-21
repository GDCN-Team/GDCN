<?php

namespace App\Models\Game\Level;

use App\Enums\Game\Level\Rating\SuggestionType;
use App\Models\Game\Level;
use App\Models\Game\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\RatingSuggestion
 *
 * @property int $id
 * @property User $user
 * @property Level $level
 * @property SuggestionType $type
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

    protected $table = 'game_level_rating_suggestions';

    protected $casts = [
        'type' => SuggestionType::class
    ];

    protected $fillable = ['type', 'user', 'level', 'rating', 'featured'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
