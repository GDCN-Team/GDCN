<?php

namespace Modules\NGProxy\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\NGProxy\Database\factories\SongFactory;

/**
 * Class Song
 *
 * @package Modules\NGProxy\Entities
 * @property int $id
 * @property string $name
 * @property int|null $author_id
 * @property string $author_name
 * @property string $size
 * @property string|null $video_id
 * @property string|null $author_youtube_url
 * @property string $download_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Modules\NGProxy\Database\factories\SongFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAuthorYoutubeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereDownloadLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereVideoId($value)
 * @mixin \Eloquent
 */
class Song extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'ngproxy_songs';

    /**
     * @return SongFactory
     */
    protected static function newFactory(): SongFactory
    {
        return SongFactory::new();
    }
}
