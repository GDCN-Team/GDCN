<?php

namespace App\Models\Game\Account\Permission;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class FlagAssign
 *
 * @package App\Models\Game\Account\Permission
 * @property int $id
 * @property int $flag
 * @property int $group
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

    /**
     * @var string
     */
    protected $table = 'game_account_permission_flag_assigns';
}
