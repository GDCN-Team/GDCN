<?php

namespace App\Http\Middleware;

use App\Models\GameAccount;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckPermission
 * @package App\Http\Middleware
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $action
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $action = null)
    {
        /** @var GameAccount $account */
        $account = $request->user();

        $can = $account->permission->can($action);
        if (!$can) {
            abort(403);
        }

        return $next($request);
    }
}
