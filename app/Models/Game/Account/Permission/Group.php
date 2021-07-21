<?php

namespace App\Models\Game\Account\Permission;

use App\Models\Game\Account;
use Database\Factories\Game\Account\Permission\GroupFactory;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Group
 *
 * @package App\Models\Game\Account\Permission
 * @property int $id
 * @property string $name
 * @property int $mod_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $comment_color
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

    /**
     * @var string
     */
    protected $table = 'game_account_permission_groups';

    /**
     * @param $flag
     * @return bool
     */
    public function give($flag): bool
    {
        return FlagAssign::query()
            ->insert([
                'flag' => Flag::query()
                    ->firstOrCreate([
                        'name' => $flag
                    ])->id,
                'group' => $this->id
            ]);
    }

    /**
     * @param $flag
     * @return bool
     */
    public function can($flag): bool
    {
        $flagID = Flag::whereName($flag)->value('id');
        if (!$flagID) {
            return false;
        }

        return FlagAssign::query()
            ->where([
                'flag' => $flagID,
                'group' => $this->id
            ])->exists();
    }

    /**
     * @param $flag
     * @return bool|null
     */
    public function remove($flag): ?bool
    {
        $flagID = Flag::whereName($flag)->value('id');
        if (!$flagID) {
            return false;
        }

        try {
            $query = FlagAssign::query()
                ->where([
                    'flag' => $flagID,
                    'group' => $this->id
                ]);

            $deleted = $query->delete();
            $query = FlagAssign::query()
                ->where([
                    'flag' => $flagID
                ]);

            if ($query->count() <= 0) {
                return $query->delete() && $deleted;
            }

            return $deleted;
        } catch (Exception $e) {
            return false;
        }
    }

    public function members()
    {
        return $this->morphToMany(Account::class, 'id');
    }
}
