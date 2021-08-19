<?php

namespace App\Services\Game;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ProxyService
{
    public string $proxy_url = 'http://localhost:10809';
    public string $geometry_dash_base_url = 'http://www.boomlings.com/database';

    public function getProxyInstance(): PendingRequest
    {
        return Http::asForm()
            ->withOptions(['proxy' => $this->proxy_url]);
    }
}
