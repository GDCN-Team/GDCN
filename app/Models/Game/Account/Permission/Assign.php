<?php

namespace App\Models\Game\Account\Permission;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Assign
 *
 * @package App\Models\Game\Account\Permission
 * @property int $id
 * @property int $account
 * @property int $group
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

    /**
     * @var string
     */
    protected $table = 'game_account_permission_assigns';
}
