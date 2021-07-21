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
 * @property int $level
 * @property int $stars
 * @property int|null $difficulty
 * @property int $featured_score
 * @property int $epic
 * @property int $coin_verified
 * @property int|null $auto
 * @property int $demon
 * @property int|null $demon_difficulty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RatingSuggestion newModelQuery()
 * @method static Builder|RatingSuggestion newQuery()
 * @method static Builder|RatingSuggestion query()
 * @method static Builder|RatingSuggestion whereAuto($value)
 * @method static Builder|RatingSuggestion whereCoinVerified($value)
 * @method static Builder|RatingSuggestion whereCreatedAt($value)
 * @method static Builder|RatingSuggestion whereDemon($value)
 * @method static Builder|RatingSuggestion whereDemonDifficulty($value)
 * @method static Builder|RatingSuggestion whereDifficulty($value)
 * @method static Builder|RatingSuggestion whereEpic($value)
 * @method static Builder|RatingSuggestion whereFeaturedScore($value)
 * @method static Builder|RatingSuggestion whereId($value)
 * @method static Builder|RatingSuggestion whereLevel($value)
 * @method static Builder|RatingSuggestion whereStars($value)
 * @method static Builder|RatingSuggestion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RatingSuggestion extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_ratings';
}
