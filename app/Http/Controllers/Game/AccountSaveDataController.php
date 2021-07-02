<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\SaveData\GetUrlRequest;
use App\Http\Requests\Game\Account\SaveData\LoadRequest;
use App\Http\Requests\Game\Account\SaveData\SaveRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

/**
 * Class AccountSaveDataController
 * @package App\Http\Controllers
 */
class AccountSaveDataController extends Controller
{
    /**
     * @var FilesystemAdapter|Filesystem
     */
    protected FilesystemAdapter|Filesystem $storage;

    /**
     * AccountSaveDataController constructor.
     */
    public function __construct()
    {
        $this->storage = App::environment('testing') ? Storage::fake('oss') : Storage::disk('oss');
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
        $this->storage->put("gdcn/saveData/{$data['userName']}.dat", $data['saveData']);
        return ResponseCode::OK;
    }

    /**
     * @param LoadRequest $request
     * @return int|string
     */
    public function load(LoadRequest $request): int|string
    {
        try {
            $data = $request->validated();
            $saveData = $this->storage->get("gdcn/saveData/{$data['userName']}.dat");
            return "$saveData;{$data['gameVersion']};{$data['binaryVersion']};$saveData";
        } catch (FileNotFoundException) {
            return ResponseCode::SAVE_DATA_NOT_FOUND;
        }
    }
}
