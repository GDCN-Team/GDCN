<?php

namespace App\Models\Game\Level;

use App\Models\Game\Level;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Rating
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
 * @method static Builder|Rating newModelQuery()
 * @method static Builder|Rating newQuery()
 * @method static Builder|Rating query()
 * @method static Builder|Rating whereAuto($value)
 * @method static Builder|Rating whereCoinVerified($value)
 * @method static Builder|Rating whereCreatedAt($value)
 * @method static Builder|Rating whereDemon($value)
 * @method static Builder|Rating whereDemonDifficulty($value)
 * @method static Builder|Rating whereDifficulty($value)
 * @method static Builder|Rating whereEpic($value)
 * @method static Builder|Rating whereFeaturedScore($value)
 * @method static Builder|Rating whereId($value)
 * @method static Builder|Rating whereLevel($value)
 * @method static Builder|Rating whereStars($value)
 * @method static Builder|Rating whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Rating extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_ratings';

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
