<?php

namespace App\Models;

use GDCN\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameSong
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property int $author_id
 * @property string $author_name
 * @property string $size
 * @property string $download_url
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameSong newModelQuery()
 * @method static Builder|GameSong newQuery()
 * @method static Builder|GameSong query()
 * @method static Builder|GameSong whereAuthorId($value)
 * @method static Builder|GameSong whereAuthorName($value)
 * @method static Builder|GameSong whereCreatedAt($value)
 * @method static Builder|GameSong whereDisabled($value)
 * @method static Builder|GameSong whereDownloadUrl($value)
 * @method static Builder|GameSong whereId($value)
 * @method static Builder|GameSong whereName($value)
 * @method static Builder|GameSong whereSize($value)
 * @method static Builder|GameSong whereUpdatedAt($value)
 * @mixin Model
 */
class GameSong extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'author_id',
        'author_name',
        'size',
        'download_url',
        'disabled'
    ];

    public function toSongString(): string
    {
        return GDObject::merge([
            1 => $this->id,
            2 => $this->name,
            3 => $this->author_id,
            4 => $this->author_name,
            5 => $this->size,
            10 => $this->download_url
        ], '~|~');
    }
}
