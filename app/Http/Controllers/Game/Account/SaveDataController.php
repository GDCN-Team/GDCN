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
    /**
     * @param SaveDataService $service
     */
    public function __construct(
        protected SaveDataService $service
    )
    {
    }

    /**
     * @param GetUrlRequest $request
     * @return string
     *
     * @return http://docs.gdprogra.me/#/endpoints/getAccountURL
     */
    public function getUrl(GetUrlRequest $request): string
    {
        return $request->getHost();
    }

    /**
     * @param SaveRequest $request
     * @return int
     */
    public function save(SaveRequest $request): int
    {
        $data = $request->validated();
        if ($this->service->save($data['userName'], $data['saveData'])) {
            return ResponseCode::ACCOUNT_DATA_SAVE_SUCCESS;
        } else {
            return ResponseCode::ACCOUNT_DATA_SAVE_FAILED;
        }
    }

    /**
     * @param LoadRequest $request
     * @return int|string
     */
    public function load(LoadRequest $request): int|string
    {
        $data = $request->validated();
        if ($result = $this->service->load($data['userName'])) {
            return $result . ';' . $data['gameVersion'] . ';' . $data['binaryVersion'] . ';' . $result;
        } else {
            return ResponseCode::ACCOUNT_DATA_LOAD_FAILED;
        }
    }
}
