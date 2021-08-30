<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class NGProxyPresenter
{
    public function renderHomePage(array $props = []): Response
    {
        return Inertia::render('NGProxy/Home', $props);
    }
}
