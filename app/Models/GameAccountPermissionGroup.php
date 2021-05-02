<?php

namespace App\Models;

use Database\Factories\GameAccountPermissionGroupFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountPermissionGroup
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property int $mod_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountPermissionGroup newModelQuery()
 * @method static Builder|GameAccountPermissionGroup newQuery()
 * @method static Builder|GameAccountPermissionGroup query()
 * @method static Builder|GameAccountPermissionGroup whereCreatedAt($value)
 * @method static Builder|GameAccountPermissionGroup whereId($value)
 * @method static Builder|GameAccountPermissionGroup whereModLevel($value)
 * @method static Builder|GameAccountPermissionGroup whereName($value)
 * @method static Builder|GameAccountPermissionGroup whereUpdatedAt($value)
 * @mixin Model
 * @property string|null $comment_color
 * @method static Builder|GameAccountPermissionGroup whereCommentColor($value)
 * @property-read Collection|GameAccount[] $members
 * @property-read int|null $members_count
 * @method static GameAccountPermissionGroupFactory factory(...$parameters)
 */
class GameAccountPermissionGroup extends Model
{
    use HasFactory;

    /**
     * @param $flag
     * @return bool
     */
    public function give($flag): bool
    {
        return GameAccountPermissionFlagAssign::query()
            ->insert([
                'flag' => GameAccountPermissionFlag::query()
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
        $flagID = GameAccountPermissionFlag::whereName($flag)->value('id');
        if (!$flagID) {
            return false;
        }

        return GameAccountPermissionFlagAssign::query()
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
        $flagID = GameAccountPermissionFlag::whereName($flag)->value('id');
        if (!$flagID) {
            return false;
        }

        try {
            $query = GameAccountPermissionFlagAssign::query()
                ->where([
                    'flag' => $flagID,
                    'group' => $this->id
                ]);

            $deleted = $query->delete();
            $query = GameAccountPermissionFlagAssign::query()
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
        return $this->morphToMany(GameAccount::class, 'id');
    }
}
