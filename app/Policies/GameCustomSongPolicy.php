<?php

namespace App\Policies;

use App\Enums\Web\Tools\Song\Types;
use App\Models\GameAccount;
use App\Models\GameCustomSong;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GameCustomSongPolicy
{
    use HandlesAuthorization;

    /**
     * @param GameAccount $account
     * @param GameCustomSong $song
     * @return Response
     */
    public function edit(GameAccount $account, GameCustomSong $song): Response
    {
        if ($song->uploader !== $account->id) {
            return $this->deny('您不是歌曲上传者');
        }

        if ($song->type === Types::NETEASE_MUSIC) {
            return $this->deny('该歌曲不可编辑');
        }

        return $this->allow();
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
