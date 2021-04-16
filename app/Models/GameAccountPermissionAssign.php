<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountPermissionAssign
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $group
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountPermissionAssign newModelQuery()
 * @method static Builder|GameAccountPermissionAssign newQuery()
 * @method static Builder|GameAccountPermissionAssign query()
 * @method static Builder|GameAccountPermissionAssign whereAccount($value)
 * @method static Builder|GameAccountPermissionAssign whereCreatedAt($value)
 * @method static Builder|GameAccountPermissionAssign whereGroup($value)
 * @method static Builder|GameAccountPermissionAssign whereId($value)
 * @method static Builder|GameAccountPermissionAssign whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountPermissionAssign extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'group'
    ];
}
