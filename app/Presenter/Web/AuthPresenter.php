<?php

namespace App\Presenter\Web;

use Inertia\Inertia;
use Inertia\Response;

/**
 * Class AuthPresenter
 * @package App\Presenter
 */
class AuthPresenter
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
