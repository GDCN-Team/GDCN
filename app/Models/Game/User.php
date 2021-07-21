<?php

namespace App\Models\Game;

use Database\Factories\Game\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Carbon;


/**
 * Class User
 *
 * @package App\Models\Game
 * @property int $id
 * @property string|null $name
 * @property string $uuid
 * @property string $udid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account|null $account
 * @property-read string $user_string
 * @property-read UserScore|null $score
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
    use Authorizable;
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'uuid',
        'udid'
    ];

    /**
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function score(): HasOne
    {
        return $this->hasOne(UserScore::class, 'user');
    }

    /**
     * @return string
     */
    public function getUserStringAttribute(): string
    {
        $uuid = is_numeric($this->uuid) ? $this->uuid : 0;
        return "{$this->id}:{$this->name}:{$uuid}";
    }
}
