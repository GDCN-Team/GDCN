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

    public function __construct()
    {
        $this->storages = [
            Storage::disk('oss')
        ];
    }

    public function put(string $name, string $content): bool
    {
        /** @var FilesystemAdapter $storage */

        foreach ($this->storages as $storage) {
            $storage->put($name, $content);
        }

        return true;
    }

    public function get(string $name): ?string
    {
        /** @var FilesystemAdapter $storage */

        foreach ($this->storages as $storage) {
            try {
                $data = $storage->get($name);
                if (!empty($data)) {
                    return $data;
                }
            } catch (FileNotFoundException) {
                continue;
            }
        }

        return null;
    }

    public function delete(string $name): bool
    {
        /** @var FilesystemAdapter $storage */

        foreach ($this->storages as $storage) {
            $storage->delete($name);
        }

        return true;
    }
}
