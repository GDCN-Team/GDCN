<?php

namespace App\Policies\Game;

use App\Models\Game\Account;
use App\Models\Game\CustomSong;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomSongPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @param CustomSong $song
     * @return bool
     */
    public function delete(Account $account, CustomSong $song): bool
    {
        return $this->edit($account, $song);
    }

    /**
     * @param Account $account
     * @param CustomSong $song
     * @return bool
     */
    public function edit(Account $account, CustomSong $song): bool
    {
        return $song->uploader === $account->id;
    }
}
