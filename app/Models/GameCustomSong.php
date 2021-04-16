<?php

namespace App\Models;

use GDCN\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class GameCustomSong
 *
 * @package App\Models
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
 * @property-read GameAccount|null $owner
 * @method static Builder|GameCustomSong newModelQuery()
 * @method static Builder|GameCustomSong newQuery()
 * @method static Builder|GameCustomSong query()
 * @method static Builder|GameCustomSong whereAuthorName($value)
 * @method static Builder|GameCustomSong whereCreatedAt($value)
 * @method static Builder|GameCustomSong whereDisabled($value)
 * @method static Builder|GameCustomSong whereDownloadUrl($value)
 * @method static Builder|GameCustomSong whereHash($value)
 * @method static Builder|GameCustomSong whereId($value)
 * @method static Builder|GameCustomSong whereName($value)
 * @method static Builder|GameCustomSong whereSize($value)
 * @method static Builder|GameCustomSong whereSongId($value)
 * @method static Builder|GameCustomSong whereType($value)
 * @method static Builder|GameCustomSong whereUpdatedAt($value)
 * @method static Builder|GameCustomSong whereUploader($value)
 * @mixin Model
 */
class GameCustomSong extends Model
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
        'song_id',
        'type',
        'name',
        'author_name',
        'download_url',
        'size',
        'uploader',
        'hash',
        'disabled'
    ];

    /**
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(GameAccount::class, 'id', 'uploader');
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
