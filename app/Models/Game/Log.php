<?php

namespace App\Models\Game;

use App\Enums\Game\LogType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Log
 *
 * @property int $id
 * @property LogType $type
 * @property string|null $value
 * @property User|null $user
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

    protected $table = 'game_logs';

    protected $casts = [
        'type' => LogType::class
    ];

    protected $fillable = ['type', 'value', 'user', 'ip'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }
}
