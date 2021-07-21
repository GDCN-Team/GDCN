<?php

namespace App\Repositories\Game;

use App\Models\Game\CustomSong;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * Class CustomSongRepository
 * @package App\Repositories
 */
class CustomSongRepository
{
    /**
     * @param array|string[] $columns
     * @return EloquentCollection|array|Collection
     */
    public function getForSongList(array $columns = ['id', 'name', 'type', 'author_name', 'download_url', 'song_id', 'size', 'uploader']): EloquentCollection|array|Collection
    {
        return CustomSong::query()
            ->with('owner:id,name')
            ->get($columns);
    }
}
