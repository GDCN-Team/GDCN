<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * Class GameChallenge
 *
 * @package App\Models
 * @property int $id
 * @property int $type
 * @property string $name
 * @property int $collect_count
 * @property int $reward_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameChallenge newModelQuery()
 * @method static Builder|GameChallenge newQuery()
 * @method static Builder|GameChallenge query()
 * @method static Builder|GameChallenge whereCollectCount($value)
 * @method static Builder|GameChallenge whereCreatedAt($value)
 * @method static Builder|GameChallenge whereId($value)
 * @method static Builder|GameChallenge whereName($value)
 * @method static Builder|GameChallenge whereRewardCount($value)
 * @method static Builder|GameChallenge whereType($value)
 * @method static Builder|GameChallenge whereUpdatedAt($value)
 * @mixin Model
 */
class GameChallenge extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_challenges';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'collect_count',
        'reward_count'
    ];
}
