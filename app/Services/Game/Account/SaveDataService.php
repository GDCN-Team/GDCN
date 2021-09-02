<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Services\Game\StorageService;
use Illuminate\Support\Facades\Cache;
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

        $account = Account::whereName($userName)->first();
        if (empty($account)) {
            return false;
        }

        $this->putCache($account, $saveData);
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

        $account = Account::whereName($userName)->first();
        if (empty($account)) {
            return null;
        }

        return $this->getSaveData($account);
    }

    protected function getObjectNameForOss(string $userName): string
    {
        return 'gdcn/saveData/' . $userName . '.dat';
    }

    protected function putCache(Account $account, string $saveData)
    {
        Cache::put("account.save.$account->id", $saveData);
    }

    protected function getSaveData(Account $account)
    {
        $cache = Cache::get("account.save.$account->id");

        if (empty($cache)) {
            $saveData = $this->storage->get(
                $this->getObjectNameForOss($account->name)
            );

            $this->putCache($account, $saveData);
            return $saveData;
        }

        return $cache;
    }
}
