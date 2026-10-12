<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Guests (and non-admins) must not learn the admin exists: plain 404, no redirect to login.
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(404);
        }

        return $next($request);
    }
}
