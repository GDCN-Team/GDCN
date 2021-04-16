<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelRating
 *
 * @package App\Models
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
 * @method static Builder|GameLevelRating newModelQuery()
 * @method static Builder|GameLevelRating newQuery()
 * @method static Builder|GameLevelRating query()
 * @method static Builder|GameLevelRating whereAuto($value)
 * @method static Builder|GameLevelRating whereCoinVerified($value)
 * @method static Builder|GameLevelRating whereCreatedAt($value)
 * @method static Builder|GameLevelRating whereDemon($value)
 * @method static Builder|GameLevelRating whereDemonDifficulty($value)
 * @method static Builder|GameLevelRating whereDifficulty($value)
 * @method static Builder|GameLevelRating whereEpic($value)
 * @method static Builder|GameLevelRating whereFeaturedScore($value)
 * @method static Builder|GameLevelRating whereId($value)
 * @method static Builder|GameLevelRating whereLevel($value)
 * @method static Builder|GameLevelRating whereStars($value)
 * @method static Builder|GameLevelRating whereUpdatedAt($value)
 * @mixin Model
 */
class GameLevelRating extends Model
{
    use HasFactory;
}
