<?php

namespace App\Policies\Game;

use App\Enums\Web\Tools\Song\Types;
use App\Models\Game\Account;
use App\Models\Game\CustomSong;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CustomSongPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @param CustomSong $song
     * @return Response
     */
    public function edit(Account $account, CustomSong $song): Response
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
     * @param Account $account
     * @param CustomSong $song
     * @return bool
     */
    public function delete(Account $account, CustomSong $song): bool
    {
        return $song->uploader === $account->id;
    }
}
