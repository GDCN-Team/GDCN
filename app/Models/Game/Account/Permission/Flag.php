<?php

namespace App\Models\Game\Account\Permission;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Permission\Flag
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|FlagAssign[] $assigns
 * @property-read int|null $assigns_count
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

    protected $table = 'game_account_permission_flags';

    protected $fillable = [
        'name'
    ];

    public function assigns(): HasMany
    {
        return $this->hasMany(FlagAssign::class, 'flag');
    }
}
