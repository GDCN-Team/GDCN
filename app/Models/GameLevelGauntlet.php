<?php

namespace App\Models;

use Database\Factories\GameLevelGauntletFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelGauntlet
 *
 * @package App\Models
 * @property int $id
 * @property int $level1
 * @property int $level2
 * @property int $level3
 * @property int $level4
 * @property int $level5
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $levels
 * @method static Builder|GameLevelGauntlet newModelQuery()
 * @method static Builder|GameLevelGauntlet newQuery()
 * @method static Builder|GameLevelGauntlet query()
 * @method static Builder|GameLevelGauntlet whereCreatedAt($value)
 * @method static Builder|GameLevelGauntlet whereId($value)
 * @method static Builder|GameLevelGauntlet whereLevel1($value)
 * @method static Builder|GameLevelGauntlet whereLevel2($value)
 * @method static Builder|GameLevelGauntlet whereLevel3($value)
 * @method static Builder|GameLevelGauntlet whereLevel4($value)
 * @method static Builder|GameLevelGauntlet whereLevel5($value)
 * @method static Builder|GameLevelGauntlet whereUpdatedAt($value)
 * @mixin Model
 * @method static GameLevelGauntletFactory factory(...$parameters)
 */
class GameLevelGauntlet extends Model
{
    use HasFactory;

    /**
     * @return string
     */
    public function getLevelsAttribute(): string
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
