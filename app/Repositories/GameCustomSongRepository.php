<?php

namespace App\Repositories;

use App\Models\GameCustomSong;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class GameCustomSongRepository
 * @package App\Repositories
 */
class GameCustomSongRepository
{
    /**
     * @param array|string[] $columns
     * @return GameCustomSong[]|Builder[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    public function getForSongList(array $columns = ['id', 'name', 'author_name', 'download_url', 'song_id', 'size', 'uploader'])
    {
        return GameCustomSong::query()
            ->with('owner:id,name')
            ->get($columns);
    }
}
