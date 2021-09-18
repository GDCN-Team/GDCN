<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class DocsPresenter
{
    public function renderCommandsPage(array $props = []): Response
    {
        return Inertia::render('Docs/Commands', $props);
    }

    public function renderHomePage(array $props = []): Response
    {
        return Inertia::render('Docs/Home', $props);
    }
}
