<?php

namespace App\Models\Game\Level;

use App\Enums\Game\Level\GauntletType;
use App\Models\Game\Level;
use Database\Factories\Game\Level\GauntletFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Gauntlet
 *
 * @property int $id
 * @property Level $level1
 * @property Level $level2
 * @property Level $level3
 * @property Level $level4
 * @property Level $level5
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property GauntletType|null $gauntlet_id
 * @method static GauntletFactory factory(...$parameters)
 * @method static Builder|Gauntlet newModelQuery()
 * @method static Builder|Gauntlet newQuery()
 * @method static Builder|Gauntlet query()
 * @method static Builder|Gauntlet whereCreatedAt($value)
 * @method static Builder|Gauntlet whereGauntletId($value)
 * @method static Builder|Gauntlet whereId($value)
 * @method static Builder|Gauntlet whereLevel1($value)
 * @method static Builder|Gauntlet whereLevel2($value)
 * @method static Builder|Gauntlet whereLevel3($value)
 * @method static Builder|Gauntlet whereLevel4($value)
 * @method static Builder|Gauntlet whereLevel5($value)
 * @method static Builder|Gauntlet whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Gauntlet extends Model
{
    use HasFactory;

    protected $table = 'game_level_gauntlets';

    protected $casts = [
        'gauntlet_id' => GauntletType::class
    ];

    protected $fillable = ['gauntlet_id', 'level1', 'level2', 'level3', 'level4', 'level5'];

    public function level1(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level1');
    }

    public function level2(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level2');
    }

    public function level3(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level3');
    }

    public function level4(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level4');
    }

    public function level5(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level5');
    }
}
