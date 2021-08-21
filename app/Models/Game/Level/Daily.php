<?php

namespace App\Models\Game\Level;

use App\Models\Game\Level;
use Database\Factories\Game\Level\DailyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Daily
 *
 * @property int $id
 * @property Level $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $time
 * @method static DailyFactory factory(...$parameters)
 * @method static Builder|Daily newModelQuery()
 * @method static Builder|Daily newQuery()
 * @method static Builder|Daily query()
 * @method static Builder|Daily whereCreatedAt($value)
 * @method static Builder|Daily whereId($value)
 * @method static Builder|Daily whereLevel($value)
 * @method static Builder|Daily whereTime($value)
 * @method static Builder|Daily whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Daily extends Model
{
    use HasFactory;

    protected $table = 'game_daily_levels';

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
