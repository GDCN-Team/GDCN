<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class GDProxyPresenter
{
    public function home(array $props = []): Response
    {
        Inertia::share('server', [
            'cdn_url' => config('app.asset_url')
        ]);

        return Inertia::render('GDProxy/Home', $props);
    }
}
