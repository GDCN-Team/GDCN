<?php

namespace App\Models\NGProxy;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\NGProxy\Song
 *
 * @property int $id
 * @property int $song_id
 * @property string $name
 * @property int $artist_id
 * @property string $artist_name
 * @property string $size
 * @property string|null $video_id
 * @property string|null $author_youtube_url
 * @property string $download_link
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Song newModelQuery()
 * @method static Builder|Song newQuery()
 * @method static Builder|Song query()
 * @method static Builder|Song whereArtistId($value)
 * @method static Builder|Song whereArtistName($value)
 * @method static Builder|Song whereAuthorYoutubeUrl($value)
 * @method static Builder|Song whereCreatedAt($value)
 * @method static Builder|Song whereDisabled($value)
 * @method static Builder|Song whereDownloadLink($value)
 * @method static Builder|Song whereId($value)
 * @method static Builder|Song whereName($value)
 * @method static Builder|Song whereSize($value)
 * @method static Builder|Song whereSongId($value)
 * @method static Builder|Song whereUpdatedAt($value)
 * @method static Builder|Song whereVideoId($value)
 * @mixin Eloquent
 * @method static \Database\Factories\NGProxy\SongFactory factory(...$parameters)
 */
class Song extends Model
{
    use HasFactory;

    protected $table = 'ngproxy_songs';
    protected $fillable = [
        'song_id',
        'name',
        'artist_id',
        'artist_name',
        'size',
        'video_id',
        'author_youtube_url',
        'download_link',
        'disabled'
    ];
}
