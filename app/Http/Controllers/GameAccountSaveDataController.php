<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Game\StorageManager;
use App\Http\Requests\GameAccountSaveDataGetUrlRequest;
use App\Http\Requests\GameAccountSaveDataLoadRequest;
use App\Http\Requests\GameAccountSaveDataSaveRequest;

/**
 * Class GameAccountSaveDataController
 * @package App\Http\Controllers
 */
class GameAccountSaveDataController extends Controller
{
    /**
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * GameAccountSaveDataController constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    /**
     * @param GameAccountSaveDataGetUrlRequest $request
     * @return string
     *
     * @return http://docs.gdprogra.me/#/endpoints/getAccountURL
     */
    public function getUrl(GameAccountSaveDataGetUrlRequest $request): string
    {
        return $request->getHost();
    }

    /**
     * @param GameAccountSaveDataSaveRequest $request
     * @return int
     */
    public function save(GameAccountSaveDataSaveRequest $request): int
    {
        $data = $request->validated();
        $this->storageManager->put(sha1($request->user()->id) . '.dat', $data['saveData']);
        return ResponseCode::OK;
    }

    /**
     * @param GameAccountSaveDataLoadRequest $request
     * @return int|string
     */
    public function load(GameAccountSaveDataLoadRequest $request)
    {
        $data = $request->validated();
        $saveData = $this->storageManager->get(sha1($request->user()->id) . '.dat');

        if (!empty($saveData)) {
            return "{$saveData};{$data['gameVersion']};{$data['binaryVersion']};{$saveData}";
        }

        return ResponseCode::SAVE_DATA_EMPTY;
    }
}
