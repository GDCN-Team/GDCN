<?php

namespace App\Models\Game;

use App\Models\Game\Level\Comment;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Gauntlet;
use App\Models\Game\Level\Rating;
use App\Models\Game\Level\RatingSuggestion;
use App\Models\Game\Level\Report;
use App\Models\Game\Level\Score;
use App\Models\Game\Level\SharedCreatorPoint;
use App\Models\Game\Level\Weekly;
use Database\Factories\Game\LevelFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Level
 *
 * @property int $id
 * @property User $user
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
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Daily|null $daily
 * @property-read Collection|Gauntlet[] $gauntlet
 * @property-read int|null $gauntlet_count
 * @property-read Rating|null $rating
 * @property-read Collection|RatingSuggestion[] $rating_suggestions
 * @property-read int|null $rating_suggestions_count
 * @property-read Report|null $report
 * @property-read Collection|Score[] $scores
 * @property-read int|null $scores_count
 * @property-read Collection|SharedCreatorPoint[] $shared_creator_points
 * @property-read int|null $shared_creator_points_count
 * @property-read Weekly|null $weekly
 * @method static LevelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Level newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level query()
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereAudioTrack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereCoins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereExtraString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereGameVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereLdm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereLevelInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereObjects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereRequestedStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereSong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereTwoPlayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereUnlisted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereVersion($value)
 * @mixin Eloquent
 */
class Level extends Model
{
    use HasFactory;

    protected $table = 'game_levels';

    protected $fillable = ['user', 'game_version', 'name', 'desc', 'downloads', 'likes', 'version', 'length', 'audio_track', 'song', 'auto', 'password', 'original', 'two_player', 'objects', 'coins', 'requested_stars', 'unlisted', 'ldm', 'extra_string', 'level_info'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'level');
    }

    public function daily(): HasOne
    {
        return $this->hasOne(Daily::class, 'level');
    }

    public function gauntlet(): HasMany
    {
        /** @var Builder $level2Query */
        $level2Query = $this->hasMany(Gauntlet::class, 'level2');

        /** @var Builder $level3Query */
        $level3Query = $this->hasMany(Gauntlet::class, 'level3');

        /** @var Builder $level4Query */
        $level4Query = $this->hasMany(Gauntlet::class, 'level4');

        /** @var Builder $level5Query */
        $level5Query = $this->hasMany(Gauntlet::class, 'level5');

        return $this->hasMany(Gauntlet::class, 'level1')
            ->union($level2Query)
            ->union($level3Query)
            ->union($level4Query)
            ->union($level5Query);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class, 'level');
    }

    public function rating_suggestions(): HasMany
    {
        return $this->hasMany(RatingSuggestion::class, 'level');
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class, 'level');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'level');
    }

    public function shared_creator_points(): HasMany
    {
        return $this->hasMany(SharedCreatorPoint::class, 'level');
    }

    public function weekly(): HasOne
    {
        return $this->hasOne(Weekly::class, 'level');
    }
}
