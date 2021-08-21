<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Setting\UpdateRequest;
use App\Services\Game\Account\SettingService;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/updateGJAccSettings20
     */
    public function update(UpdateRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->update($data['accountID'], $data['mS'], $data['frS'], $data['cS'], $data['yt'], $data['twitter'], $data['twitch'])) {
            return ResponseCode::ACCOUNT_SETTING_UPDATE_FAILED;
        }

        return ResponseCode::ACCOUNT_SETTING_UPDATE_SUCCESS;
    }
}
