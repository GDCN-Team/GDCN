<?php

namespace App\Policies;

use App\Enums\GameCustomSongType;
use App\Models\GameAccount;
use App\Models\GameCustomSong;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class GameSongPolicy
 * @package App\Policies\Models
 */
class GameSongPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $account
     * @param GameCustomSong $song
     * @return bool
     */
    public function edit(GameAccount $account, GameCustomSong $song): bool
    {
        return $song->uploader === $account->id
            && $song->type !== GameCustomSongType::NETEASE_MUSIC;
    }

    /**
     * @param GameAccount $account
     * @param GameCustomSong $song
     * @return bool
     */
    public function delete(GameAccount $account, GameCustomSong $song): bool
    {
        return $song->uploader === $account->id;
    }
}
