<?php

namespace App\Services\Game;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

/**
 * Class StorageService
 * @package App\Services\Game\Account
 */
class StorageService
{
    protected array $storages;

    /**
     * StorageService constructor.
     */
    public function __construct()
    {
        $this->storages = [
            Storage::disk('oss')
        ];
    }

    /**
     * @param string $name
     * @param string $content
     * @param bool $cancelWhenFailed
     * @return bool
     */
    public function put(string $name, string $content, bool $cancelWhenFailed = true): bool
    {
        /** @var FilesystemAdapter $storage */

        foreach ($this->storages as $storage) {
            if (!$storage->put($name, $content) && $cancelWhenFailed) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function get(string $name): ?string
    {
        /** @var FilesystemAdapter $storage */

        foreach ($this->storages as $storage) {
            try {
                if ($result = $storage->get($name)) {
                    return $result;
                }
            } catch (FileNotFoundException) {
                continue;
            }
        }

        return null;
    }
}
