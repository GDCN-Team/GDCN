<?php

namespace App\Models\Game;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\CustomSong
 *
 * @property int $id
 * @property int $song_id
 * @property string $type
 * @property int $uploader
 * @property string $name
 * @property string $author_name
 * @property string $size
 * @property string $download_url
 * @property string $hash
 * @property int $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    protected $table = 'game_custom_songs';
}
