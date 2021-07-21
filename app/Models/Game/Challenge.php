<?php

namespace App\Models\Game;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * Class Challenge
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $type
 * @property string $name
 * @property int $collect_count
 * @property int $reward_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Challenge newModelQuery()
 * @method static Builder|Challenge newQuery()
 * @method static Builder|Challenge query()
 * @method static Builder|Challenge whereCollectCount($value)
 * @method static Builder|Challenge whereCreatedAt($value)
 * @method static Builder|Challenge whereId($value)
 * @method static Builder|Challenge whereName($value)
 * @method static Builder|Challenge whereRewardCount($value)
 * @method static Builder|Challenge whereType($value)
 * @method static Builder|Challenge whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Challenge extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_challenges';
}
