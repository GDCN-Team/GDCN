<?php

namespace App\Models\Game;

use Eloquent;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class CustomSong
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $song_id
 * @property int $type
 * @property int $uploader
 * @property string $name
 * @property string $author_name
 * @property string $size
 * @property string $download_url
 * @property string $hash
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account|null $owner
 * @method static Builder|CustomSong newModelQuery()
 * @method static Builder|CustomSong newQuery()
 * @method static Builder|CustomSong query()
 * @method static Builder|CustomSong whereAuthorName($value)
 * @method static Builder|CustomSong whereCreatedAt($value)
 * @method static Builder|CustomSong whereDisabled($value)
 * @method static Builder|CustomSong whereDownloadUrl($value)
 * @method static Builder|CustomSong whereHash($value)
 * @method static Builder|CustomSong whereId($value)
 * @method static Builder|CustomSong whereName($value)
 * @method static Builder|CustomSong whereSize($value)
 * @method static Builder|CustomSong whereSongId($value)
 * @method static Builder|CustomSong whereType($value)
 * @method static Builder|CustomSong whereUpdatedAt($value)
 * @method static Builder|CustomSong whereUploader($value)
 * @mixin Eloquent
 */
class CustomSong extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_custom_songs';

    /**
     * @var string[]
     */
    protected $fillable = [
        'hash'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'uploader' => 'integer'
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'uploader');
    }

    public function toSongString(): string
    {
        return GDObject::merge([
            1 => $this->song_id,
            2 => $this->name,
            3 => 9,
            4 => $this->author_name,
            5 => $this->size,
            10 => $this->download_url
        ], '~|~');
    }
}
