<?php

namespace App\Models\Game;

use Database\Factories\Game\UserScoreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


/**
 * Class UserScore
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $user
 * @property int $game_version
 * @property int $binary_version
 * @property int $stars
 * @property int $demons
 * @property int $diamonds
 * @property int $icon
 * @property int $color1
 * @property int $color2
 * @property int $icon_type
 * @property int $coins
 * @property int $user_coins
 * @property int $special
 * @property int $acc_icon
 * @property int $acc_ship
 * @property int $acc_ball
 * @property int $acc_bird
 * @property int $acc_dart
 * @property int $acc_robot
 * @property int $acc_glow
 * @property int $acc_spider
 * @property int $acc_explosion
 * @property int $creator_points
 * @property int $chest1count
 * @property Carbon|null $chest1time
 * @property int $chest2count
 * @property Carbon|null $chest2time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $owner
 * @method static Builder|UserScore newModelQuery()
 * @method static Builder|UserScore newQuery()
 * @method static Builder|UserScore query()
 * @method static Builder|UserScore whereAccBall($value)
 * @method static Builder|UserScore whereAccBird($value)
 * @method static Builder|UserScore whereAccDart($value)
 * @method static Builder|UserScore whereAccExplosion($value)
 * @method static Builder|UserScore whereAccGlow($value)
 * @method static Builder|UserScore whereAccIcon($value)
 * @method static Builder|UserScore whereAccRobot($value)
 * @method static Builder|UserScore whereAccShip($value)
 * @method static Builder|UserScore whereAccSpider($value)
 * @method static Builder|UserScore whereBinaryVersion($value)
 * @method static Builder|UserScore whereChest1count($value)
 * @method static Builder|UserScore whereChest1time($value)
 * @method static Builder|UserScore whereChest2count($value)
 * @method static Builder|UserScore whereChest2time($value)
 * @method static Builder|UserScore whereCoins($value)
 * @method static Builder|UserScore whereColor1($value)
 * @method static Builder|UserScore whereColor2($value)
 * @method static Builder|UserScore whereCreatedAt($value)
 * @method static Builder|UserScore whereCreatorPoints($value)
 * @method static Builder|UserScore whereDemons($value)
 * @method static Builder|UserScore whereDiamonds($value)
 * @method static Builder|UserScore whereGameVersion($value)
 * @method static Builder|UserScore whereIcon($value)
 * @method static Builder|UserScore whereIconType($value)
 * @method static Builder|UserScore whereId($value)
 * @method static Builder|UserScore whereSpecial($value)
 * @method static Builder|UserScore whereStars($value)
 * @method static Builder|UserScore whereUpdatedAt($value)
 * @method static Builder|UserScore whereUser($value)
 * @method static Builder|UserScore whereUserCoins($value)
 * @mixin Eloquent
 * @method static UserScoreFactory factory(...$parameters)
 */
class UserScore extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_user_scores';

    /**
     * @var string[]
     */
    protected $casts = [
        'chest1time' => 'datetime',
        'chest2time' => 'datetime'
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user');
    }
}
