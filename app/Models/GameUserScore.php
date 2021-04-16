<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


/**
 * Class GameUserScore
 *
 * @package App\Models
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
 * @property-read GameUser|null $owner
 * @method static Builder|GameUserScore newModelQuery()
 * @method static Builder|GameUserScore newQuery()
 * @method static Builder|GameUserScore query()
 * @method static Builder|GameUserScore whereAccBall($value)
 * @method static Builder|GameUserScore whereAccBird($value)
 * @method static Builder|GameUserScore whereAccDart($value)
 * @method static Builder|GameUserScore whereAccExplosion($value)
 * @method static Builder|GameUserScore whereAccGlow($value)
 * @method static Builder|GameUserScore whereAccIcon($value)
 * @method static Builder|GameUserScore whereAccRobot($value)
 * @method static Builder|GameUserScore whereAccShip($value)
 * @method static Builder|GameUserScore whereAccSpider($value)
 * @method static Builder|GameUserScore whereBinaryVersion($value)
 * @method static Builder|GameUserScore whereChest1count($value)
 * @method static Builder|GameUserScore whereChest1time($value)
 * @method static Builder|GameUserScore whereChest2count($value)
 * @method static Builder|GameUserScore whereChest2time($value)
 * @method static Builder|GameUserScore whereCoins($value)
 * @method static Builder|GameUserScore whereColor1($value)
 * @method static Builder|GameUserScore whereColor2($value)
 * @method static Builder|GameUserScore whereCreatedAt($value)
 * @method static Builder|GameUserScore whereCreatorPoints($value)
 * @method static Builder|GameUserScore whereDemons($value)
 * @method static Builder|GameUserScore whereDiamonds($value)
 * @method static Builder|GameUserScore whereGameVersion($value)
 * @method static Builder|GameUserScore whereIcon($value)
 * @method static Builder|GameUserScore whereIconType($value)
 * @method static Builder|GameUserScore whereId($value)
 * @method static Builder|GameUserScore whereSpecial($value)
 * @method static Builder|GameUserScore whereStars($value)
 * @method static Builder|GameUserScore whereUpdatedAt($value)
 * @method static Builder|GameUserScore whereUser($value)
 * @method static Builder|GameUserScore whereUserCoins($value)
 * @mixin Model
 */
class GameUserScore extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'game_version',
        'binary_version',
        'stars',
        'demons',
        'diamonds',
        'icon',
        'color1',
        'color2',
        'icon_type',
        'coins',
        'user_coins',
        'special',
        'acc_icon',
        'acc_ship',
        'acc_ball',
        'acc_bird',
        'acc_dart',
        'acc_robot',
        'acc_glow',
        'acc_spider',
        'acc_explosion'
    ];

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
        return $this->hasOne(GameUser::class, 'id', 'user');
    }
}
