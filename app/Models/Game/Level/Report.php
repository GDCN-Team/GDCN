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
 * App\Models\Game\Level\Report
 *
 * @property int $id
 * @property Level $level
 * @property int $times
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Report newModelQuery()
 * @method static Builder|Report newQuery()
 * @method static Builder|Report query()
 * @method static Builder|Report whereCreatedAt($value)
 * @method static Builder|Report whereId($value)
 * @method static Builder|Report whereLevel($value)
 * @method static Builder|Report whereTimes($value)
 * @method static Builder|Report whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Report extends Model
{
    use HasFactory;

    protected $table = 'game_level_reports';

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
