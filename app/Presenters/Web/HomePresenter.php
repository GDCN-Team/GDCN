<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class HomePresenter
{
    /**
     * @param array $props
     * @return Response
     */
    public function renderHomePage(array $props = []): Response
    {
        Inertia::share('server', [
            'app_name' => config('app.name'),
            'cdn_url' => config('app.asset_url')
        ]);

        return Inertia::render('Home', $props);
    }
}
