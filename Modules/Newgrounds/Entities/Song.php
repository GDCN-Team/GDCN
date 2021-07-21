<?php

namespace Modules\Newgrounds\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Newgrounds\Database\factories\SongFactory;

/**
 * Class Song
 *
 * @package Modules\Newgrounds\Entities
 * @property int $id
 * @property string $name
 * @property int|null $author_id
 * @property string $author_name
 * @property string $size
 * @property string|null $youtube_video_id
 * @property string|null $author_youtube_url
 * @property string $download_url
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static SongFactory factory(...$parameters)
 * @method static Builder|Song newModelQuery()
 * @method static Builder|Song newQuery()
 * @method static Builder|Song query()
 * @method static Builder|Song whereAuthorId($value)
 * @method static Builder|Song whereAuthorName($value)
 * @method static Builder|Song whereAuthorYoutubeUrl($value)
 * @method static Builder|Song whereCreatedAt($value)
 * @method static Builder|Song whereDisabled($value)
 * @method static Builder|Song whereDownloadUrl($value)
 * @method static Builder|Song whereId($value)
 * @method static Builder|Song whereName($value)
 * @method static Builder|Song whereSize($value)
 * @method static Builder|Song whereUpdatedAt($value)
 * @method static Builder|Song whereYoutubeVideoId($value)
 * @mixin Eloquent
 */
class Song extends Model
{
    use HasFactory;

    protected $table = 'newgrounds_songs';

    protected static function newFactory()
    {
        return SongFactory::new();
    }
}
