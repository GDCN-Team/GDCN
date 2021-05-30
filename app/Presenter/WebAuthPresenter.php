<?php

namespace App\Presenter;

use Inertia\Inertia;
use Inertia\Response;

/**
 * Class WebAuthPresenter
 * @package App\Presenter
 */
class WebAuthPresenter
{
    /**
     * @param array $data
     * @return Response
     */
    public function login(array $data = []): Response
    {
        return Inertia::render('Auth/Login', $data);
    }
}
