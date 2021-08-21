<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\PasswordReset
 *
 * @property int $id
 * @property Account $account
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    protected $table = 'game_account_password_resets';

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }
}
