<?php

namespace App\Models\Game;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Contest
 *
 * @package App\Models\Game
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string|null $expired_at
 * @property int $owner
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ContestInformation[] $info
 * @property-read int|null $info_count
 * @method static Builder|Contest newModelQuery()
 * @method static Builder|Contest newQuery()
 * @method static Builder|Contest query()
 * @method static Builder|Contest whereCreatedAt($value)
 * @method static Builder|Contest whereDesc($value)
 * @method static Builder|Contest whereExpiredAt($value)
 * @method static Builder|Contest whereId($value)
 * @method static Builder|Contest whereName($value)
 * @method static Builder|Contest whereOwner($value)
 * @method static Builder|Contest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Contest extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_contests';

    /**
     * @return HasMany
     */
    public function info(): HasMany
    {
        return $this->hasMany(ContestInformation::class, 'contest');
    }
}
