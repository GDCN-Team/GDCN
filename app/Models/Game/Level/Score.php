<?php

namespace App\Models\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level;
use Database\Factories\Game\Level\ScoreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Score
 *
 * @property int $id
 * @property Account $account
 * @property Level $level
 * @property int $percent
 * @property int $attempts
 * @property int $coins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    protected $table = 'game_level_scores';

    protected $fillable = ['account', 'attempts', 'percent', 'coins'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
