<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as RequestFacade;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!AuthFacade::check()) {
            return $this->process();
        }

        return $next($request);
    }

    /**
     * @return RedirectResponse
     */
    public function process(): RedirectResponse
    {
        $url = RequestFacade::fullUrl();
        return Redirect::route('auth.login', ['intended' => $url])->with('url.intended', $url);
    }
}
