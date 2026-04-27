<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && !$request->ajax() && !$request->wantsJson()) {
            try {
                $today = now()->toDateString();

                // Atomic upsert: insert or increment page_views
                DB::statement(
                    'INSERT INTO visitor_stats (date, page_views, unique_visitors) VALUES (?, 1, 0)
                     ON DUPLICATE KEY UPDATE page_views = page_views + 1',
                    [$today]
                );

                // Count unique visitor once per session per day
                $sessionKey = 'visited_' . $today;
                if (session()->isStarted() && !session()->has($sessionKey)) {
                    session()->put($sessionKey, true);
                    DB::statement(
                        'INSERT INTO visitor_stats (date, page_views, unique_visitors) VALUES (?, 0, 1)
                         ON DUPLICATE KEY UPDATE unique_visitors = unique_visitors + 1',
                        [$today]
                    );
                }
            } catch (\Throwable) {
                // Never let tracking break the site
            }
        }

        return $response;
    }
}
