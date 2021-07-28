<?php

namespace App\Models\Game\Level;

use App\Models\Game\Level;
use Database\Factories\Game\Level\GauntletFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Gauntlet
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $level1
 * @property int $level2
 * @property int $level3
 * @property int $level4
 * @property int $level5
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $level_ids
 * @property-read array $levels
 * @method static GauntletFactory factory(...$parameters)
 * @method static Builder|Gauntlet newModelQuery()
 * @method static Builder|Gauntlet newQuery()
 * @method static Builder|Gauntlet query()
 * @method static Builder|Gauntlet whereCreatedAt($value)
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

    /**
     * @var string
     */
    protected $table = 'game_level_gauntlets';

    /**
     * @return array
     */
    public function getLevelsAttribute(): array
    {
        return [
            Level::find($this->level1),
            Level::find($this->level2),
            Level::find($this->level3),
            Level::find($this->level4),
            Level::find($this->level5)
        ];
    }

    /**
     * @return string
     */
    public function getLevelIdsAttribute(): string
    {
        return implode(',', [
            $this->level1,
            $this->level2,
            $this->level3,
            $this->level4,
            $this->level5
        ]);
    }
}
