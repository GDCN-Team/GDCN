<?php

namespace App\Models;

use App\Game\Helpers;
use App\Models\GameLevelComment as LevelCommentModel;
use App\Models\GameLevelRating as LevelRatingModel;
use App\Models\GameUser as UserModel;
use Database\Factories\GameLevelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class GameLevel
 *
 * @package App\Models
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
 * @property-read LevelRatingModel|null $rating
 * @method static Builder|GameLevel newModelQuery()
 * @method static Builder|GameLevel newQuery()
 * @method static Builder|GameLevel query()
 * @method static Builder|GameLevel whereAudioTrack($value)
 * @method static Builder|GameLevel whereAuto($value)
 * @method static Builder|GameLevel whereCoins($value)
 * @method static Builder|GameLevel whereCreatedAt($value)
 * @method static Builder|GameLevel whereDesc($value)
 * @method static Builder|GameLevel whereDownloads($value)
 * @method static Builder|GameLevel whereExtraString($value)
 * @method static Builder|GameLevel whereGameVersion($value)
 * @method static Builder|GameLevel whereId($value)
 * @method static Builder|GameLevel whereLdm($value)
 * @method static Builder|GameLevel whereLength($value)
 * @method static Builder|GameLevel whereLevelInfo($value)
 * @method static Builder|GameLevel whereLikes($value)
 * @method static Builder|GameLevel whereName($value)
 * @method static Builder|GameLevel whereObjects($value)
 * @method static Builder|GameLevel whereOriginal($value)
 * @method static Builder|GameLevel wherePassword($value)
 * @method static Builder|GameLevel whereRequestedStars($value)
 * @method static Builder|GameLevel whereSong($value)
 * @method static Builder|GameLevel whereTwoPlayer($value)
 * @method static Builder|GameLevel whereUnlisted($value)
 * @method static Builder|GameLevel whereUpdatedAt($value)
 * @method static Builder|GameLevel whereUser($value)
 * @method static Builder|GameLevel whereVersion($value)
 * @mixin Model
 * @property-read bool $rated
 * @method static GameLevelFactory factory(...$parameters)
 */
class GameLevel extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_levels';

    /**
     * @var string[]
     */
    protected $casts = [
        'user' => 'integer'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user',
        'game_version',
        'name',
        'desc',
        'downloads',
        'likes',
        'version',
        'length',
        'audio_track',
        'song',
        'auto',
        'password',
        'original',
        'two_player',
        'objects',
        'coins',
        'requested_stars',
        'unlisted',
        'ldm',
        'extra_string',
        'level_info'
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
     * @param $stars
     * @return GameLevelRating
     */
    public function rate($stars): LevelRatingModel
    {
        /** @var GameLevelRating $rating */
        $rating = $this->rating()
            ->firstOrNew();

        $helper = app(Helpers::class);
        $rating->level = $this->id;
        $rating->stars = $stars;
        $rating->difficulty = $helper->guessDiffFromStars($stars)[1];
        $rating->featured_score = 0;
        $rating->epic = false;
        $rating->coin_verified = false;
        $rating->auto = $stars === 1;
        $rating->demon = $stars >= 10;
        $rating->demon_difficulty = 0;
        $rating->save();

        return $rating;
    }

    /**
     * @return HasOne
     */
    public function rating(): HasOne
    {
        return $this->hasOne(LevelRatingModel::class, 'level');
    }
}
