<?php

namespace App\Services\Game\Account;

use App\Services\Game\StorageService;
use Illuminate\Support\Facades\Log;

class SaveDataService
{
    public function __construct(
        protected StorageService $storage
    )
    {
    }

    public function save(string $userName, string $saveData): bool
    {
        Log::channel('gdcn')
            ->info('[Account SaveData System] Action: Save Data', [
                'userName' => $userName
            ]);

        return $this->storage->put(
            $this->getObjectNameForOss($userName),
            $saveData
        );
    }

    public function load(string $userName): ?string
    {
        Log::channel('gdcn')
            ->info('[Account SaveData System] Action: Load Data', [
                'userName' => $userName
            ]);

        return $this->storage->get(
            $this->getObjectNameForOss($userName)
        );
    }

    protected function getObjectNameForOss(string $userName): string
    {
        return 'gdcn/saveData/' . $userName . '.dat';
    }
}
