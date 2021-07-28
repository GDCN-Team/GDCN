<?php

namespace Modules\Proxy\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

/**
 * Class ProxyController
 * @package Modules\Proxy\Http\Controllers
 */
class ProxyController extends Controller
{
    /**
     * @var string
     */
    public string $proxyServer = 'http://127.0.0.1:10809';

    /**
     * @return PendingRequest
     */
    public function getInstance(): PendingRequest
    {
        return Http::withOptions(['proxy' => $this->proxyServer]);
    }
}
