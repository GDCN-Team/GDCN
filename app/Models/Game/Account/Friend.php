<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account;
use Database\Factories\Game\Account\FriendFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Friend
 *
 * @property int $id
 * @property Account $account
 * @property Account $target_account
 * @property int $new
 * @property int $target_new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FriendFactory factory(...$parameters)
 * @method static Builder|Friend newModelQuery()
 * @method static Builder|Friend newQuery()
 * @method static Builder|Friend query()
 * @method static Builder|Friend whereAccount($value)
 * @method static Builder|Friend whereCreatedAt($value)
 * @method static Builder|Friend whereId($value)
 * @method static Builder|Friend whereNew($value)
 * @method static Builder|Friend whereTargetAccount($value)
 * @method static Builder|Friend whereTargetNew($value)
 * @method static Builder|Friend whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Friend extends Model
{
    use HasFactory;

    protected $table = 'game_account_friends';

    protected $fillable = ['target_account'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function target_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'target_account');
    }
}
