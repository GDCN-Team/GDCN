<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class AdminPresenter
{
    public function renderHomePage(array $props = []): Response
    {
        return Inertia::render('Admin/Home', $props);
    }
}
