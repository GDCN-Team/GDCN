<?php

namespace App\Models\Game\Level;

use Database\Factories\Game\Level\PackFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Pack
 *
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
 * @method static PackFactory factory(...$parameters)
 * @method static Builder|Pack newModelQuery()
 * @method static Builder|Pack newQuery()
 * @method static Builder|Pack query()
 * @method static Builder|Pack whereBarColor($value)
 * @method static Builder|Pack whereCoins($value)
 * @method static Builder|Pack whereCreatedAt($value)
 * @method static Builder|Pack whereDifficulty($value)
 * @method static Builder|Pack whereId($value)
 * @method static Builder|Pack whereLevels($value)
 * @method static Builder|Pack whereName($value)
 * @method static Builder|Pack whereStars($value)
 * @method static Builder|Pack whereTextColor($value)
 * @method static Builder|Pack whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Pack extends Model
{
    use HasFactory;

    protected $table = 'game_level_packs';
}
