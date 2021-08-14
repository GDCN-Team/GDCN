<?php

namespace App\Models\Game;

use Database\Factories\Game\SongFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Song
 *
 * @property int $id
 * @property int $song_id
 * @property string $name
 * @property int $artist_id
 * @property string $artist_name
 * @property string $size
 * @property string $video_id
 * @property string $author_youtube_url
 * @property string $download_link
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static SongFactory factory(...$parameters)
 * @method static Builder|Song newModelQuery()
 * @method static Builder|Song newQuery()
 * @method static Builder|Song query()
 * @method static Builder|Song whereArtistId($value)
 * @method static Builder|Song whereArtistName($value)
 * @method static Builder|Song whereAuthorYoutubeUrl($value)
 * @method static Builder|Song whereCreatedAt($value)
 * @method static Builder|Song whereDownloadLink($value)
 * @method static Builder|Song whereId($value)
 * @method static Builder|Song whereName($value)
 * @method static Builder|Song whereSize($value)
 * @method static Builder|Song whereSongId($value)
 * @method static Builder|Song whereUpdatedAt($value)
 * @method static Builder|Song whereVideoId($value)
 * @mixin Eloquent
 */
class Song extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'ngproxy_songs';
}
