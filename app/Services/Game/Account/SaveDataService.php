<?php

namespace App\Services\Game\Account;

use App\Services\Game\StorageService;

/**
 * Class SaveDataService
 * @package App\Services\Game\Account
 */
class SaveDataService
{

    /**
     * SaveDataService constructor.
     * @param StorageService $storage
     */
    public function __construct(
        protected StorageService $storage
    )
    {
    }

    /**
     * @param string $userName
     * @param string $saveData
     * @return bool
     */
    public function save(string $userName, string $saveData): bool
    {
        return $this->storage->put(
            $this->generateFileName($userName),
            $saveData
        );
    }

    /**
     * @param string $userName
     * @return string
     */
    protected function generateFileName(string $userName): string
    {
        return 'gdcn/saveData/' . $userName . '.dat';
    }

    /**
     * @param string $userName
     * @return string|null
     */
    public function load(string $userName): ?string
    {
        return $this->storage->get(
            $this->generateFileName($userName)
        );
    }
}
