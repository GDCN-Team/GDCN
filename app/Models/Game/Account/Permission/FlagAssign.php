<?php

namespace App\Models\Game\Account\Permission;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Permission\FlagAssign
 *
 * @property int $id
 * @property Flag $flag
 * @property Group $group
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FlagAssign newModelQuery()
 * @method static Builder|FlagAssign newQuery()
 * @method static Builder|FlagAssign query()
 * @method static Builder|FlagAssign whereCreatedAt($value)
 * @method static Builder|FlagAssign whereFlag($value)
 * @method static Builder|FlagAssign whereGroup($value)
 * @method static Builder|FlagAssign whereId($value)
 * @method static Builder|FlagAssign whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FlagAssign extends Model
{
    use HasFactory;

    protected $table = 'game_account_permission_flag_assigns';

    protected $fillable = [
        'group'
    ];

    public function flag(): BelongsTo
    {
        return $this->belongsTo(Flag::class, 'flag');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group');
    }
}
