<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Game\StorageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\SaveData\GetUrlRequest;
use App\Http\Requests\Game\Account\SaveData\LoadRequest;
use App\Http\Requests\Game\Account\SaveData\SaveRequest;

/**
 * Class AccountSaveDataController
 * @package App\Http\Controllers
 */
class AccountSaveDataController extends Controller
{
    /**
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * AccountSaveDataController constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
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
        $this->storageManager->put(sha1($request->user()->id) . '.dat', $data['saveData']);
        return ResponseCode::OK;
    }

    /**
     * @param LoadRequest $request
     * @return int|string
     */
    public function load(LoadRequest $request)
    {
        $data = $request->validated();
        $saveData = $this->storageManager->get(sha1($request->user()->id) . '.dat');

        if (!empty($saveData)) {
            return "{$saveData};{$data['gameVersion']};{$data['binaryVersion']};{$saveData}";
        }

        return ResponseCode::SAVE_DATA_EMPTY;
    }
}
