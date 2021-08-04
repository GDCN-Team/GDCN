<?php

namespace App\Models\Game\Level;

use App\Models\Game\Account;
use Database\Factories\Game\Level\ScoreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Score
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $account
 * @property int $level
 * @property int $percent
 * @property int $attempts
 * @property int $coins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account|null $owner
 * @method static ScoreFactory factory(...$parameters)
 * @method static Builder|Score newModelQuery()
 * @method static Builder|Score newQuery()
 * @method static Builder|Score query()
 * @method static Builder|Score whereAccount($value)
 * @method static Builder|Score whereAttempts($value)
 * @method static Builder|Score whereCoins($value)
 * @method static Builder|Score whereCreatedAt($value)
 * @method static Builder|Score whereId($value)
 * @method static Builder|Score whereLevel($value)
 * @method static Builder|Score wherePercent($value)
 * @method static Builder|Score whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Score extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_scores';

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'level',
        'attempts',
        'percent',
        'coins'
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'account');
    }
}
