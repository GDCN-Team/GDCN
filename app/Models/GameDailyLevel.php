<?php

namespace App\Models;

use Database\Factories\GameDailyLevelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameDailyLevel
 *
 * @package App\Models
 * @property int $id
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameDailyLevel newModelQuery()
 * @method static Builder|GameDailyLevel newQuery()
 * @method static Builder|GameDailyLevel query()
 * @method static Builder|GameDailyLevel whereCreatedAt($value)
 * @method static Builder|GameDailyLevel whereId($value)
 * @method static Builder|GameDailyLevel whereLevel($value)
 * @method static Builder|GameDailyLevel whereUpdatedAt($value)
 * @mixin Model
 * @property string $time
 * @method static GameDailyLevelFactory factory(...$parameters)
 * @method static Builder|GameDailyLevel whereTime($value)
 */
class GameDailyLevel extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_daily_levels';
}
