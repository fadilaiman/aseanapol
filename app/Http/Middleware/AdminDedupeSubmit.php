<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Double-submit guard for admin forms. The admin layout JS puts a random "_once" token into every POST form when the
 * page loads. A second request carrying the same token (double click, impatient re-send, back + resubmit) is not
 * processed again, so articles/events are never created twice. Requests without a token are unaffected.
 */
class AdminDedupeSubmit
{
    public function handle(Request $request, Closure $next)
    {
        // PHP empties $_POST/$_FILES when the body exceeds post_max_size: never process such a request as if it were empty.
        $len = (int) $request->server('CONTENT_LENGTH', 0);
        $max = ini_to_bytes(ini_get('post_max_size'));
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD') && $len > 0
            && ($len > $max || (str_contains((string) $request->header('Content-Type'), 'multipart/') && !$request->all() && !$request->allFiles()))) {
            throw new \Illuminate\Http\Exceptions\PostTooLargeException();
        }

        $token = $request->input('_once');
        if ($request->isMethod('GET') || $request->isMethod('HEAD') || !is_string($token) || !preg_match('/^[A-Za-z0-9]{16,64}$/', $token)) {
            return $next($request);
        }

        $key = 'admin_once:' . ($request->user()?->getAuthIdentifier() ?? 'x') . ':' . $token;
        if (!Cache::add($key, 1, now()->addMinutes(30))) {
            return redirect()->to(url()->previous(route('admin.dashboard')))
                ->with('error', 'This form was already submitted, so it was not processed a second time. Check the list to confirm your change is there.');
        }

        return $next($request);
    }
}
