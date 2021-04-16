<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class GameContest
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string|null $expired_at
 * @property int $owner
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|GameContestInformation[] $info
 * @property-read int|null $info_count
 * @method static Builder|GameContest newModelQuery()
 * @method static Builder|GameContest newQuery()
 * @method static Builder|GameContest query()
 * @method static Builder|GameContest whereCreatedAt($value)
 * @method static Builder|GameContest whereDesc($value)
 * @method static Builder|GameContest whereExpiredAt($value)
 * @method static Builder|GameContest whereId($value)
 * @method static Builder|GameContest whereName($value)
 * @method static Builder|GameContest whereOwner($value)
 * @method static Builder|GameContest whereUpdatedAt($value)
 * @mixin Model
 */
class GameContest extends Model
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
        return $this->hasMany(GameContestInformation::class, 'contest');
    }
}
