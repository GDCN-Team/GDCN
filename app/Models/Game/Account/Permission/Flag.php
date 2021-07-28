<?php

namespace App\Models\Game\Account\Permission;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Flag
 *
 * @package App\Models\Game\Account\Permission
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Flag newModelQuery()
 * @method static Builder|Flag newQuery()
 * @method static Builder|Flag query()
 * @method static Builder|Flag whereCreatedAt($value)
 * @method static Builder|Flag whereId($value)
 * @method static Builder|Flag whereName($value)
 * @method static Builder|Flag whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Flag extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_permission_flags';
}
