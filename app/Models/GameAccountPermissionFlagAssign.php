<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountPermissionFlagAssign
 *
 * @package App\Models
 * @property int $id
 * @property int $flag
 * @property int $group
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountPermissionFlagAssign newModelQuery()
 * @method static Builder|GameAccountPermissionFlagAssign newQuery()
 * @method static Builder|GameAccountPermissionFlagAssign query()
 * @method static Builder|GameAccountPermissionFlagAssign whereCreatedAt($value)
 * @method static Builder|GameAccountPermissionFlagAssign whereFlag($value)
 * @method static Builder|GameAccountPermissionFlagAssign whereGroup($value)
 * @method static Builder|GameAccountPermissionFlagAssign whereId($value)
 * @method static Builder|GameAccountPermissionFlagAssign whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountPermissionFlagAssign extends Model
{
    use HasFactory;
}
