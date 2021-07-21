<?php

namespace App\Models\Game;

use App\Enums\Game\Log\Types;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

/**
 * Class Log
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $type
 * @property string|null $value
 * @property int|null $user
 * @property string $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Log newModelQuery()
 * @method static Builder|Log newQuery()
 * @method static Builder|Log query()
 * @method static Builder|Log whereCreatedAt($value)
 * @method static Builder|Log whereId($value)
 * @method static Builder|Log whereIp($value)
 * @method static Builder|Log whereType($value)
 * @method static Builder|Log whereUpdatedAt($value)
 * @method static Builder|Log whereUser($value)
 * @method static Builder|Log whereValue($value)
 * @mixin Eloquent
 */
class Log extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_logs';

    /**
     * Return true if log created, return false if log exists
     *
     * @param Types $type
     * @param $value
     * @param User|null $user
     * @param bool $useIP
     * @param bool $alwaysNew
     * @return bool
     */
    public function existsOrNew(Types $type, $value, ?User $user = null, bool $useIP = false, bool $alwaysNew = false): bool
    {
        $data = [
            'type' => $type->value,
            'value' => $value,
            'user' => $user->id ?? null
        ];

        $ip = [
            'ip' => Request::ip()
        ];

        if ($useIP) {
            $data = array_merge($data, $ip);
        }

        $query = self::query();
        $log = $alwaysNew ? $query->create(array_merge($data, $ip)) : $query->firstOrNew(array_merge($data, $ip));

        if ($log->exists()) {
            return false;
        }

        return $log->save();
    }
}
