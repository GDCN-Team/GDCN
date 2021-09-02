<?php

namespace App\Http\Middleware;

use App\Models\Game\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountPermissionCanMiddleware
{
    public function handle(Request $request, Closure $next, string $flag): mixed
    {
        return $next($request); # TODO: remove

        /** @var Account $account */
        $account = Auth::user();

        if (!$account->permission_group?->can($flag)) {
            abort(403);
        }

        return $next($request);
    }
}
