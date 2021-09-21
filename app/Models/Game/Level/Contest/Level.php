<?php

namespace App\Models\Game\Level\Contest;

use App\Models\Game\Level as GameLevel;
use App\Models\Game\Level\Contest;
use App\Models\Game\Level\Contest\Level\Flag;
use App\Models\Game\Level\Contest\Level\FlagAssign;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Contest\Level
 *
 * @property int $id
 * @property int $contestID
 * @property int $levelID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Level newModelQuery()
 * @method static Builder|Level newQuery()
 * @method static Builder|Level query()
 * @method static Builder|Level whereContestID($value)
 * @method static Builder|Level whereCreatedAt($value)
 * @method static Builder|Level whereId($value)
 * @method static Builder|Level whereLevelID($value)
 * @method static Builder|Level whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Level extends Model
{
    use HasFactory;

    protected $table = 'game_level_contest_levels';

    protected $fillable = ['contestID', 'levelID'];

    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class, 'contestID');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(GameLevel::class, 'levelID');
    }

    public function flags(): HasManyThrough
    {
        return $this->hasManyThrough(Flag::class, FlagAssign::class, 'flagID', 'id');
    }
}
