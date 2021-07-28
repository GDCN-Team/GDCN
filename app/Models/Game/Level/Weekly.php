<?php

namespace App\Models\Game\Level;

use Database\Factories\Game\Level\WeeklyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Weekly
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $time
 * @method static WeeklyFactory factory(...$parameters)
 * @method static Builder|Weekly newModelQuery()
 * @method static Builder|Weekly newQuery()
 * @method static Builder|Weekly query()
 * @method static Builder|Weekly whereCreatedAt($value)
 * @method static Builder|Weekly whereId($value)
 * @method static Builder|Weekly whereLevel($value)
 * @method static Builder|Weekly whereTime($value)
 * @method static Builder|Weekly whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Weekly extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_weekly_levels';
}
