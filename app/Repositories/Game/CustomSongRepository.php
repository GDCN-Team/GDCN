<?php

namespace App\Repositories\Game;

use App\Models\GameCustomSong;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class CustomSongRepository
 * @package App\Repositories
 */
class CustomSongRepository
{
    /**
     * @param array|string[] $columns
     * @return GameCustomSong[]|Builder[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    public function getForSongList(array $columns = ['id', 'name', 'type', 'author_name', 'download_url', 'song_id', 'size', 'uploader'])
    {
        return GameCustomSong::query()
            ->with('owner:id,name')
            ->get($columns);
    }
}
