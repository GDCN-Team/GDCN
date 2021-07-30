<?php

namespace Modules\GDProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\GDProxy\Database\factories\ReplaceSongLevelFactory;

/**
 * Class ReplaceSongLevel
 *
 * @package Modules\GDProxy\Entities
 * @property int $id
 * @property int $level
 * @property int $song
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ReplaceSongLevelFactory factory(...$parameters)
 * @method static Builder|ReplaceSongLevel newModelQuery()
 * @method static Builder|ReplaceSongLevel newQuery()
 * @method static Builder|ReplaceSongLevel query()
 * @method static Builder|ReplaceSongLevel whereCreatedAt($value)
 * @method static Builder|ReplaceSongLevel whereId($value)
 * @method static Builder|ReplaceSongLevel whereLevel($value)
 * @method static Builder|ReplaceSongLevel whereSong($value)
 * @method static Builder|ReplaceSongLevel whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ReplaceSongLevel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'gdproxy_replace_song_levels';

    protected static function newFactory(): ReplaceSongLevelFactory
    {
        return ReplaceSongLevelFactory::new();
    }
}
