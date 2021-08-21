<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected string $route = 'auth.login';

    protected string $message = '请先登录';

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route($this->route);
        }

        abort(401);
    }
}
