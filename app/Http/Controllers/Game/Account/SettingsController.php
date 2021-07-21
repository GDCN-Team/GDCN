<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Setting\UpdateRequest;
use App\Services\Game\Account\SettingService;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 */
class SettingsController extends Controller
{
    /**
     * @var SettingService
     */
    protected $service;

    /**
     * SettingsController constructor.
     * @param SettingService $service
     */
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @param UpdateRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJAccSettings20
     */
    public function update(UpdateRequest $request): int
    {
        $data = $request->validated();
        return $this->service->update($data['accountID'], $data['mS'], $data['frS'], $data['cS'], $data['yt'], $data['twitter'], $data['twitch'])
            ? ResponseCode::ACCOUNT_SETTING_UPDATE_SUCCESS : ResponseCode::ACCOUNT_SETTING_UPDATE_FAILED;
    }
}
