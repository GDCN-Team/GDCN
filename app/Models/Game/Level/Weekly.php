<?php

namespace App\Models\Game\Level;

use App\Models\Game\Level;
use Database\Factories\Game\Level\WeeklyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Weekly
 *
 * @property int $id
 * @property Level $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $time
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

    protected $table = 'game_weekly_levels';

    protected $casts = [
        'time' => 'datetime'
    ];

    protected $fillable = ['time'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
