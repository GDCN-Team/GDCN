<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameAccountSettingUpdateRequest;
use App\Models\GameAccountSetting;

/**
 * Class AccountSettingsController
 * @package App\Http\Controllers
 */
class AccountSettingsController extends Controller
{
    /**
     * @param GameAccountSettingUpdateRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJAccSettings20
     */
    public function update(GameAccountSettingUpdateRequest $request): int
    {
        $data = $request->validated();
        GameAccountSetting::query()
            ->updateOrCreate([
                'account' => $data['accountID']
            ], [
                'message_state' => $data['mS'],
                'friend_request_state' => $data['frS'],
                'comment_history_state' => $data['cS'],
                'youtube' => $data['yt'],
                'twitter' => $data['twitter'],
                'twitch' => $data['twitch']
            ]);

        return ResponseCode::OK;
    }
}
