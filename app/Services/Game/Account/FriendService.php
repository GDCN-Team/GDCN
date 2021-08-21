<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account\Friend;
use Illuminate\Support\Facades\Log;

class FriendService
{
    public function remove(int $accountID, int $targetAccountID): bool
    {
        Log::channel('gdcn')
            ->info('[Account Friend System] Action: Remove Friend', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID
            ]);

        return Friend::where([
            'account' => $accountID,
            'target_account' => $targetAccountID
        ])->orWhere([
            'account' => $targetAccountID
        ])->where([
            'target_account' => $accountID
        ])->delete();
    }
}
