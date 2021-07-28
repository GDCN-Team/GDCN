<?php

namespace App\Policies\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Link;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @param Link $link
     * @return bool
     */
    public function unlink(Account $account, Link $link): bool
    {
        return $link->owner->is($account);
    }
}
