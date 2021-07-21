<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class PasswordReset
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property int $account
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account|null $acc
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereAccount($value)
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|PasswordReset whereId($value)
 * @method static Builder|PasswordReset whereToken($value)
 * @method static Builder|PasswordReset whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PasswordReset extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_password_resets';

    /**
     * @return HasOne
     */
    public function acc(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'account');
    }
}
