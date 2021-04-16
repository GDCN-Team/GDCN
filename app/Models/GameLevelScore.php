<?php

namespace App\Models;

use App\Models\GameAccount as AccountModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class GameLevelScore
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $level
 * @property int $percent
 * @property int $attempts
 * @property int $coins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AccountModel|null $owner
 * @method static Builder|GameLevelScore newModelQuery()
 * @method static Builder|GameLevelScore newQuery()
 * @method static Builder|GameLevelScore query()
 * @method static Builder|GameLevelScore whereAccount($value)
 * @method static Builder|GameLevelScore whereAttempts($value)
 * @method static Builder|GameLevelScore whereCoins($value)
 * @method static Builder|GameLevelScore whereCreatedAt($value)
 * @method static Builder|GameLevelScore whereId($value)
 * @method static Builder|GameLevelScore whereLevel($value)
 * @method static Builder|GameLevelScore wherePercent($value)
 * @method static Builder|GameLevelScore whereUpdatedAt($value)
 * @mixin Model
 */
class GameLevelScore extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'level',
        'attempts',
        'coins',
        'percent'
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(AccountModel::class, 'id', 'account');
    }
}
