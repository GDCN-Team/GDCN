<?php

namespace App\Models;

use Database\Factories\GameWeeklyLevelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameWeeklyLevel
 *
 * @package App\Models
 * @property int $id
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameWeeklyLevel newModelQuery()
 * @method static Builder|GameWeeklyLevel newQuery()
 * @method static Builder|GameWeeklyLevel query()
 * @method static Builder|GameWeeklyLevel whereCreatedAt($value)
 * @method static Builder|GameWeeklyLevel whereId($value)
 * @method static Builder|GameWeeklyLevel whereLevel($value)
 * @method static Builder|GameWeeklyLevel whereUpdatedAt($value)
 * @mixin Model
 * @property string $time
 * @method static GameWeeklyLevelFactory factory(...$parameters)
 * @method static Builder|GameWeeklyLevel whereTime($value)
 */
class GameWeeklyLevel extends Model
{
    use HasFactory;
}
