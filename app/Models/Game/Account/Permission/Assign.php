<?php

namespace App\Models\Game\Account\Permission;

use App\Models\Game\Account;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Permission\Assign
 *
 * @property int $id
 * @property Account $account
 * @property Group $group
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Assign newModelQuery()
 * @method static Builder|Assign newQuery()
 * @method static Builder|Assign query()
 * @method static Builder|Assign whereAccount($value)
 * @method static Builder|Assign whereCreatedAt($value)
 * @method static Builder|Assign whereGroup($value)
 * @method static Builder|Assign whereId($value)
 * @method static Builder|Assign whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Assign extends Model
{
    use HasFactory;

    protected $table = 'game_account_permission_assigns';

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group');
    }
}
