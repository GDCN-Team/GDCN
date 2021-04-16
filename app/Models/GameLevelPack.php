<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelPack
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $levels
 * @property int $stars
 * @property int $coins
 * @property int $difficulty
 * @property string $text_color
 * @property string|null $bar_color
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameLevelPack newModelQuery()
 * @method static Builder|GameLevelPack newQuery()
 * @method static Builder|GameLevelPack query()
 * @method static Builder|GameLevelPack whereBarColor($value)
 * @method static Builder|GameLevelPack whereCoins($value)
 * @method static Builder|GameLevelPack whereCreatedAt($value)
 * @method static Builder|GameLevelPack whereDifficulty($value)
 * @method static Builder|GameLevelPack whereId($value)
 * @method static Builder|GameLevelPack whereLevels($value)
 * @method static Builder|GameLevelPack whereName($value)
 * @method static Builder|GameLevelPack whereStars($value)
 * @method static Builder|GameLevelPack whereTextColor($value)
 * @method static Builder|GameLevelPack whereUpdatedAt($value)
 * @mixin Model
 */
class GameLevelPack extends Model
{
    use HasFactory;
}
