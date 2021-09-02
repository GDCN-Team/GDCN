<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Setting;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function update(
        int     $accountID,
        int     $messageState,
        bool    $friendRequestState,
        int     $commentState,
        ?string $youtube,
        ?string $twitter,
        ?string $twitch
    ): Setting
    {
        /** @var Setting $setting */
        $setting = Account::find($accountID)
            ->setting()
            ->updateOrCreate([], [
                'message_state' => $messageState,
                'friend_request_state' => $friendRequestState,
                'comment_history_state' => $commentState,
                'youtube' => $youtube,
                'twitter' => $twitter,
                'twitch' => $twitch
            ]);

        Log::channel('gdcn')
            ->info('[Account Setting System] Action: Update Setting', [
                'accountID' => $accountID,
                'messageState' => $messageState,
                'friendRequestState' => $friendRequestState,
                'commentState' => $commentState,
                'youtube' => $youtube,
                'twitter' => $twitch,
                'twitch' => $twitch
            ]);

        $setting->save();
        return $setting;
    }
}
