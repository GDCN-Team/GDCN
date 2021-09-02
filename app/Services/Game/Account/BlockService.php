<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use Illuminate\Support\Facades\Log;

class BlockService
{
    public function block(int $accountID, int $targetAccountID): Block
    {
        /** @var Block $block */
        $block = Account::findOrFail($accountID)
            ->blocks()
            ->firstOrCreate([
                'target_account' => $targetAccountID
            ]);

        Log::channel('gdcn')
            ->info('[Account Block System] Action: Blocked', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID
            ]);

        $block->save();
        return $block;
    }

    public function unblock(int $accountID, int $targetAccountID): bool
    {
        Log::channel('gdcn')
            ->info('[Account Block System] Action: UnBlocked', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID
            ]);

        return Account::findOrFail($accountID)
            ->blocks()
            ->where('target_account', $targetAccountID)
            ->delete();
    }
}
