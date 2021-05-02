<?php

namespace App\Models;

use Database\Factories\GameUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Carbon;


/**
 * Class GameUser
 *
 * @package App\Models
 * @property int $id
 * @property string|null $name
 * @property string $uuid
 * @property string $udid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read GameAccount|null $account
 * @property-read string $user_string
 * @property-read GameUserScore|null $score
 * @method static Builder|GameUser newModelQuery()
 * @method static Builder|GameUser newQuery()
 * @method static Builder|GameUser query()
 * @method static Builder|GameUser whereCreatedAt($value)
 * @method static Builder|GameUser whereId($value)
 * @method static Builder|GameUser whereName($value)
 * @method static Builder|GameUser whereUdid($value)
 * @method static Builder|GameUser whereUpdatedAt($value)
 * @method static Builder|GameUser whereUuid($value)
 * @mixin Model
 * @method static GameUserFactory factory(...$parameters)
 */
class GameUser extends Model
{
    use Authorizable;
    use HasFactory;

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
        return $this->hasOne(GameAccount::class, 'id', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function score(): HasOne
    {
        return $this->hasOne(GameUserScore::class, 'user');
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
