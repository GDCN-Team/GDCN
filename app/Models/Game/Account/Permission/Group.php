<?php

namespace App\Models\Game\Account\Permission;

use App\Models\Game\Account;
use Database\Factories\Game\Account\Permission\GroupFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Permission\Group
 *
 * @property int $id
 * @property string $name
 * @property int $mod_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $comment_color
 * @property-read Collection|Flag[] $flags
 * @property-read int|null $flags_count
 * @property-read Collection|Account[] $members
 * @property-read int|null $members_count
 * @method static GroupFactory factory(...$parameters)
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCommentColor($value)
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereModLevel($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Group extends Model
{
    use HasFactory;

    protected $table = 'game_account_permission_groups';

    protected $fillable = ['name', 'mod_level'];

    public function flags(): HasManyThrough
    {
        return $this->hasManyThrough(Flag::class, FlagAssign::class, 'group', 'id', 'id', 'flag');
    }

    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(Account::class, Assign::class, 'group', 'id', 'id', 'account');
    }

    public function can(string $flag): bool
    {
        return $this->flags()
            ->where('name', $flag)
            ->exists();
    }
}
