<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\SaveData\GetUrlRequest;
use App\Http\Requests\Game\Account\SaveData\LoadRequest;
use App\Http\Requests\Game\Account\SaveData\SaveRequest;
use App\Services\Game\Account\SaveDataService;

class SaveDataController extends Controller
{
    public function __construct(
        protected SaveDataService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getAccountURL
     */
    public function getUrl(GetUrlRequest $request): string
    {
        return $request->getHost();
    }

    public function save(SaveRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->save($data['userName'], $data['saveData'])) {
            return ResponseCode::ACCOUNT_DATA_SAVE_FAILED;
        }

        return ResponseCode::ACCOUNT_DATA_SAVE_SUCCESS;
    }

    public function load(LoadRequest $request): int|string
    {
        $data = $request->validated();
        if (!$saveData = $this->service->load($data['userName'])) {
            return ResponseCode::ACCOUNT_DATA_LOAD_FAILED;
        }

        return implode(';', [
            $saveData,
            $data['gameVersion'],
            $data['binaryVersion'],
            $saveData
        ]);
    }
}
