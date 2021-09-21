<?php

namespace App\Models\Game\Level\Contest\Level;

use App\Models\Game\Level\Contest\Level;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Contest\Level\FlagAssign
 *
 * @property int $id
 * @property int $flagID
 * @property int $levelID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FlagAssign newModelQuery()
 * @method static Builder|FlagAssign newQuery()
 * @method static Builder|FlagAssign query()
 * @method static Builder|FlagAssign whereCreatedAt($value)
 * @method static Builder|FlagAssign whereFlagID($value)
 * @method static Builder|FlagAssign whereId($value)
 * @method static Builder|FlagAssign whereLevelID($value)
 * @method static Builder|FlagAssign whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FlagAssign extends Model
{
    use HasFactory;

    protected $table = 'game_level_contest_level_flag_assigns';

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'levelID');
    }

    public function flag(): BelongsTo
    {
        return $this->belongsTo(Flag::class, 'flagID');
    }
}
