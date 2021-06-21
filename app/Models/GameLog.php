<?php

namespace App\Models;

use App\Enums\Game\LogType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

/**
 * Class GameLog
 *
 * @package App\Models
 * @property int $id
 * @property int $type
 * @property string|null $value
 * @property int|null $user
 * @property string $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameLog newModelQuery()
 * @method static Builder|GameLog newQuery()
 * @method static Builder|GameLog query()
 * @method static Builder|GameLog whereCreatedAt($value)
 * @method static Builder|GameLog whereId($value)
 * @method static Builder|GameLog whereIp($value)
 * @method static Builder|GameLog whereType($value)
 * @method static Builder|GameLog whereUpdatedAt($value)
 * @method static Builder|GameLog whereUser($value)
 * @method static Builder|GameLog whereValue($value)
 * @mixin Model
 */
class GameLog extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'value',
        'ip',
        'user'
    ];

    /**
     * Return true if log created, return false if log exists
     *
     * @param LogType $type
     * @param $value
     * @param GameUser|null $user
     * @param bool $useIP
     * @param bool $alwaysNew
     * @return bool
     */
    public function existsOrNew(LogType $type, $value, ?GameUser $user = null, bool $useIP = false, bool $alwaysNew = false): bool
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
