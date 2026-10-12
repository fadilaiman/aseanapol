<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Optional ADMIN_ALLOWED_IPS (comma-separated IPs / CIDRs). Empty = allow all.
 * Anyone else gets the standard 404, indistinguishable from a non-existent URL.
 */
class AdminIpAllow
{
    public function handle(Request $request, Closure $next)
    {
        $list = array_values(array_filter(array_map('trim', explode(',', (string) config('app.admin_allowed_ips', '')))));

        if ($list && ! IpUtils::checkIp((string) $request->ip(), $list)) {
            abort(404);
        }

        return $next($request);
    }
}
