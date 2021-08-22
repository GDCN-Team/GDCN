<?php

namespace App\Services\Game;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyService
{
    public string $proxy_url = 'http://localhost:60007';
    public string $geometry_dash_base_url = 'http://www.boomlings.com/database';

    public function getProxyInstance(): PendingRequest
    {
        Log::channel('gdcn')
            ->info('[Base Proxy System] Action: Get Base Proxy Instance');

        return Http::asForm()
            ->withUserAgent(null)
            ->withOptions([
                'proxy' => $this->proxy_url
            ]);
    }
}
