<?php

namespace App\Models\Game;

use Database\Factories\Game\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\User
 *
 * @property int $id
 * @property string|null $name
 * @property string $uuid
 * @property string $udid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account $account
 * @property-read Collection|Level[] $levels
 * @property-read int|null $levels_count
 * @property-read UserScore $score
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereUdid($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @mixin Eloquent
 */
class User extends Model
{
    use HasFactory;

    protected $table = 'game_users';

    protected $fillable = ['name', 'uuid', 'udid'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uuid');
    }

    public function score(): HasOne
    {
        return $this->hasOne(UserScore::class, 'user')->withDefault([
            'icon' => 0,
            'color1' => 0,
            'color2' => 3,
            'icon_type' => 0,
            'special' => 0,
        ]);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'user');
    }
}
