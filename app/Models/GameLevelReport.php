<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelReport
 *
 * @package App\Models
 * @property int $id
 * @property int $level
 * @property int $times
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameLevelReport newModelQuery()
 * @method static Builder|GameLevelReport newQuery()
 * @method static Builder|GameLevelReport query()
 * @method static Builder|GameLevelReport whereCreatedAt($value)
 * @method static Builder|GameLevelReport whereId($value)
 * @method static Builder|GameLevelReport whereLevel($value)
 * @method static Builder|GameLevelReport whereTimes($value)
 * @method static Builder|GameLevelReport whereUpdatedAt($value)
 * @mixin Model
 */
class GameLevelReport extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'level',
        'times'
    ];
}
