<?php

namespace App\Models\Game;

use App\Enums\Game\BanType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\BannedUser
 *
 * @property int $id
 * @property User $user
 * @property BanType $type
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BannedUser newModelQuery()
 * @method static Builder|BannedUser newQuery()
 * @method static Builder|BannedUser query()
 * @method static Builder|BannedUser whereCreatedAt($value)
 * @method static Builder|BannedUser whereId($value)
 * @method static Builder|BannedUser whereReason($value)
 * @method static Builder|BannedUser whereType($value)
 * @method static Builder|BannedUser whereUpdatedAt($value)
 * @method static Builder|BannedUser whereUser($value)
 * @mixin Eloquent
 * @property string|null $expired_at
 * @method static Builder|BannedUser whereExpiredAt($value)
 */
class BannedUser extends Model
{
    use HasFactory;

    protected $table = 'game_banned_users';

    protected $casts = [
        'type' => BanType::class
    ];

    protected $fillable = ['user', 'type', 'reason'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }
}
