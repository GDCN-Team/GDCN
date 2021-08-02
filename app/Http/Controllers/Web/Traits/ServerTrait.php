<?php

namespace App\Http\Controllers\Web\Traits;

use Illuminate\Support\Str;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\Proxy\Http\Controllers\ProxyController;

/**
 * Trait ServerTrait
 * @package App\Http\Controllers\Web\Traits
 */
trait ServerTrait
{
    /**
     * @var array|string[]
     */
    protected array $servers = [
        'official' => '官服'
    ];

    /**
     * @param string $serverSymbol
     * @param string $path
     * @param array $data
     * @return string|null
     */
    public function requestServer(string $serverSymbol, string $path, array $data = []): ?string
    {
        switch ($serverSymbol) {
            case 'official':
                $proxy = app(ProxyController::class);
                $GDProxy = app(GDProxyController::class);
                return $proxy->getInstance()
                    ->asForm()
                    ->post($GDProxy->gdServer . Str::start($path, '/'), $data)
                    ->body();
            default:
                return null;
        }
    }
}
