<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountPasswordReset
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read GameAccount|null $acc
 * @method static Builder|GameAccountPasswordReset newModelQuery()
 * @method static Builder|GameAccountPasswordReset newQuery()
 * @method static Builder|GameAccountPasswordReset query()
 * @method static Builder|GameAccountPasswordReset whereAccount($value)
 * @method static Builder|GameAccountPasswordReset whereCreatedAt($value)
 * @method static Builder|GameAccountPasswordReset whereId($value)
 * @method static Builder|GameAccountPasswordReset whereToken($value)
 * @method static Builder|GameAccountPasswordReset whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountPasswordReset extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function acc(): HasOne
    {
        return $this->hasOne(GameAccount::class, 'id', 'account');
    }
}
