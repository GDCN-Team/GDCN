<?php

namespace App\Http\Controllers\Web\Traits;

use App\Models\Game\Account;
use Illuminate\Support\Facades\Auth;

/**
 * Trait AuthTrait
 * @package App\Http\Controllers\Web\Traits
 */
trait AuthTrait
{
    use ResponseTrait;
    protected bool $mustVerifyEmail = false;

    /**
     * @return Account
     */
    protected function getAccount(): Account
    {
        /** @var Account $account */
        $account = Auth::user();

        if (!$account) {
            abort(401);
        }

        if ($this->mustVerifyEmail && !$account->hasVerifiedEmail()) {
            abort(403);
        }

        return $account;
    }
}
