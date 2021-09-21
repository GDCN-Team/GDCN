<?php

namespace App\Models\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level\Contest\Level;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\Contest
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int $accountID
 * @property string $expired_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Contest newModelQuery()
 * @method static Builder|Contest newQuery()
 * @method static Builder|Contest query()
 * @method static Builder|Contest whereAccountID($value)
 * @method static Builder|Contest whereCreatedAt($value)
 * @method static Builder|Contest whereDesc($value)
 * @method static Builder|Contest whereExpiredAt($value)
 * @method static Builder|Contest whereId($value)
 * @method static Builder|Contest whereName($value)
 * @method static Builder|Contest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Contest extends Model
{
    use HasFactory;

    protected $table = 'game_level_contests';

    protected $fillable = ['name', 'desc', 'accountID', 'expired_at'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'accountID');
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'contestID');
    }
}
