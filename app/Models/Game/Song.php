<?php

namespace App\Models\Game;

use Database\Factories\Game\SongFactory;
use Eloquent;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Song
 *
 * @package App\Models\Game
 * @property int $id
 * @property string $name
 * @property int $author_id
 * @property string $author_name
 * @property string $size
 * @property string $download_url
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Song newModelQuery()
 * @method static Builder|Song newQuery()
 * @method static Builder|Song query()
 * @method static Builder|Song whereAuthorId($value)
 * @method static Builder|Song whereAuthorName($value)
 * @method static Builder|Song whereCreatedAt($value)
 * @method static Builder|Song whereDisabled($value)
 * @method static Builder|Song whereDownloadUrl($value)
 * @method static Builder|Song whereId($value)
 * @method static Builder|Song whereName($value)
 * @method static Builder|Song whereSize($value)
 * @method static Builder|Song whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static SongFactory factory(...$parameters)
 */
class Song extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_songs';

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
