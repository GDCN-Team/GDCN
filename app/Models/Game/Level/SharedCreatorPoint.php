<?php

namespace App\Models\Game\Level;

use App\Models\Game\Level;
use App\Models\Game\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level\SharedCreatorPoint
 *
 * @property int $id
 * @property Level $level
 * @property User $user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SharedCreatorPoint newModelQuery()
 * @method static Builder|SharedCreatorPoint newQuery()
 * @method static Builder|SharedCreatorPoint query()
 * @method static Builder|SharedCreatorPoint whereCreatedAt($value)
 * @method static Builder|SharedCreatorPoint whereId($value)
 * @method static Builder|SharedCreatorPoint whereLevel($value)
 * @method static Builder|SharedCreatorPoint whereUpdatedAt($value)
 * @method static Builder|SharedCreatorPoint whereUser($value)
 * @mixin Eloquent
 */
class SharedCreatorPoint extends Model
{
    use HasFactory;

    protected $table = 'game_level_shared_creator_points';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level');
    }
}
