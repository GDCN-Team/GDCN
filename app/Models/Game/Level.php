<?php

namespace App\Models\Game;

use App\Models\Game\Level\Comment as LevelCommentModel;
use App\Models\Game\Level\Rating as LevelRatingModel;
use App\Models\Game\User as UserModel;
use Database\Factories\Game\LevelFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Level
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $user
 * @property int $game_version
 * @property string $name
 * @property string|null $desc
 * @property int $downloads
 * @property int $likes
 * @property int $version
 * @property int $length
 * @property int $audio_track
 * @property int $song
 * @property int $auto
 * @property int $password
 * @property int $original
 * @property int $two_player
 * @property int $objects
 * @property int $coins
 * @property int $requested_stars
 * @property int $unlisted
 * @property int $ldm
 * @property string $extra_string
 * @property string $level_info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|LevelCommentModel[] $comments
 * @property-read int|null $comments_count
 * @property-read UserModel|null $creator
 * @property-read bool $rated
 * @property-read LevelRatingModel|null $rating
 * @method static LevelFactory factory(...$parameters)
 * @method static Builder|Level newModelQuery()
 * @method static Builder|Level newQuery()
 * @method static Builder|Level query()
 * @method static Builder|Level whereAudioTrack($value)
 * @method static Builder|Level whereAuto($value)
 * @method static Builder|Level whereCoins($value)
 * @method static Builder|Level whereCreatedAt($value)
 * @method static Builder|Level whereDesc($value)
 * @method static Builder|Level whereDownloads($value)
 * @method static Builder|Level whereExtraString($value)
 * @method static Builder|Level whereGameVersion($value)
 * @method static Builder|Level whereId($value)
 * @method static Builder|Level whereLdm($value)
 * @method static Builder|Level whereLength($value)
 * @method static Builder|Level whereLevelInfo($value)
 * @method static Builder|Level whereLikes($value)
 * @method static Builder|Level whereName($value)
 * @method static Builder|Level whereObjects($value)
 * @method static Builder|Level whereOriginal($value)
 * @method static Builder|Level wherePassword($value)
 * @method static Builder|Level whereRequestedStars($value)
 * @method static Builder|Level whereSong($value)
 * @method static Builder|Level whereTwoPlayer($value)
 * @method static Builder|Level whereUnlisted($value)
 * @method static Builder|Level whereUpdatedAt($value)
 * @method static Builder|Level whereUser($value)
 * @method static Builder|Level whereVersion($value)
 * @mixin Eloquent
 */
class Level extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_levels';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user' => 'integer'
    ];

    /**
     * @return bool
     */
    public function getRatedAttribute(): bool
    {
        return !is_null($this->rating);
    }

    /**
     * @return HasOne
     */
    public function creator(): HasOne
    {
        return $this->hasOne(UserModel::class, 'id', 'user');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(LevelCommentModel::class, 'level');
    }

    /**
     * @return HasOne
     */
    public function rating(): HasOne
    {
        return $this->hasOne(LevelRatingModel::class, 'level');
    }
}
