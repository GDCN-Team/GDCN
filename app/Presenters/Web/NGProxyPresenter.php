<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class NGProxyPresenter
{
    public function home(array $props = []): Response
    {
        return Inertia::render('NGProxy/Home', $props);
    }
}
