<?php

namespace Modules\NGProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\NGProxy\Database\factories\CustomSongFactory;

/**
 * Class CustomSong
 *
 * @package Modules\NGProxy\Entities
 * @property int $id
 * @property int $song_id
 * @property string $name
 * @property string $author_name
 * @property string $size
 * @property string $download_link
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static CustomSongFactory factory(...$parameters)
 * @method static Builder|CustomSong newModelQuery()
 * @method static Builder|CustomSong newQuery()
 * @method static Builder|CustomSong query()
 * @method static Builder|CustomSong whereAuthorName($value)
 * @method static Builder|CustomSong whereCreatedAt($value)
 * @method static Builder|CustomSong whereDisabled($value)
 * @method static Builder|CustomSong whereDownloadLink($value)
 * @method static Builder|CustomSong whereId($value)
 * @method static Builder|CustomSong whereName($value)
 * @method static Builder|CustomSong whereSize($value)
 * @method static Builder|CustomSong whereSongId($value)
 * @method static Builder|CustomSong whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CustomSong extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'ngproxy_custom_songs';

    protected static function newFactory(): CustomSongFactory
    {
        return CustomSongFactory::new();
    }
}
