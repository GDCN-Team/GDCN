<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Setting as AccountSetting;
use App\Services\Game\HelperService;

/**
 * Class SettingService
 * @package App\Services\Game\Account
 */
class SettingService
{
    /**
     * SettingService constructor.
     * @param HelperService $helper
     */
    public function __construct(
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int $messageState
     * @param bool $friendRequestState
     * @param int $commentState
     * @param string $youtube
     * @param string $twitter
     * @param string $twitch
     * @return bool
     */
    public function update(Account|int $account, int $messageState, bool $friendRequestState, int $commentState, string $youtube, string $twitter, string $twitch): bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $setting = $account->setting ?? new AccountSetting();
        $setting->account = $account->id;
        $setting->message_state = $messageState;
        $setting->friend_request_state = $friendRequestState;
        $setting->comment_history_state = $commentState;
        $setting->youtube = $youtube;
        $setting->twitter = $twitter;
        $setting->twitch = $twitch;
        return $setting->save();
    }
}
