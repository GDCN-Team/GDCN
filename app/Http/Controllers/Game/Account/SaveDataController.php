<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\SaveData\GetUrlRequest;
use App\Http\Requests\Game\Account\SaveData\LoadRequest;
use App\Http\Requests\Game\Account\SaveData\SaveRequest;
use App\Services\Game\Account\SaveDataService;

/**
 * Class SaveDataController
 * @package App\Http\Controllers
 */
class SaveDataController extends Controller
{
    /**
     * @var SaveDataService
     */
    protected $service;

    /**
     * SaveDataController constructor.
     * @param SaveDataService $service
     */
    public function __construct(SaveDataService $service)
    {
        $this->service = $service;
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
        return $this->service->save($data['userName'], $data['saveData'])
            ? ResponseCode::ACCOUNT_DATA_SAVE_SUCCESS : ResponseCode::ACCOUNT_DATA_SAVE_FAILED;
    }

    /**
     * @param LoadRequest $request
     * @return int|string
     */
    public function load(LoadRequest $request)
    {
        $data = $request->validated();
        $result = $this->service->load($data['userName']);
        return !empty($result) ? $result . ';' . $data['gameVersion'] . ';' . $data['binaryVersion'] . ';' . $result : ResponseCode::ACCOUNT_DATA_LOAD_FAILED;
    }
}
