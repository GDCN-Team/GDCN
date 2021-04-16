<?php

namespace App\Game;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class StorageManager
 * @package App\Game
 */
class StorageManager
{
    /**
     * @var array
     */
    protected $storages;

    /**
     * StorageManager constructor.
     * @param mixed $storages
     */
    public function __construct($storages)
    {
        $this->storages = $storages;
    }

    /**
     * @param callable $callback
     * @param bool $once
     * @return array|string|bool|mixed
     */
    public function do(callable $callback, bool $once = false)
    {
        foreach ($this->storages as $storage) {
            if (!empty($storage['disk']) && !empty($storage['path'])) {

                if (App::environment('local')) {
                    $storage['path'] .= '/local';
                }

                $result = $callback($storage);

                if ($once) {
                    return $result;
                }

                $results[] = $result;
            }
        }

        return $results ?? [];
    }

    /**
     * @param $path
     * @return mixed
     */
    public function get($path)
    {
        return $this->do(function($storage) use ($path) {
            $path = Str::start($path, '/');
            try {
                return Storage::disk($storage['disk'])->get("{$storage['path']}{$path}");
            } catch (Exception $e) {
                return null;
            }
        }, true);
    }

    /**
     * @param $path
     * @param $contents
     * @param array $options
     * @return bool[]
     */
    public function put($path, $contents, $options = [])
    {
        return $this->do(function($storage) use ($contents, $options, $path) {
            $path = Str::start($path, '/');
            return Storage::disk($storage['disk'])->put("{$storage['path']}{$path}", $contents, $options);
        });
    }

    /**
     * @param $path
     * @return bool[]
     */
    public function delete($path)
    {
        return $this->do(function($storage) use ($path) {
            $path = Str::start($path, '/');
            return Storage::disk($storage['disk'])->delete("{$storage['path']}{$path}");
        });
    }
}
