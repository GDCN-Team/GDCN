<?php

namespace App\Models\Game\Level;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class SharedCreatorPoint
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $level
 * @property int $user
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

    /**
     * @var string
     */
    protected $table = 'game_level_shared_creator_points';
}
