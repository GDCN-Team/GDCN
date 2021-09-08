<?php

namespace App\Http\Middleware;

use App\Models\Game\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CheckAccountPermissionCanMiddleware
{
    public function handle(Request $request, Closure $next, string $flag): mixed
    {
        if (App::isLocal()) {
            return $next($request);
        }

        /** @var Account $account */
        $account = Auth::user();

        if (!$account->permission_group?->can($flag)) {
            abort(403);
        }

        return $next($request);
    }
}
