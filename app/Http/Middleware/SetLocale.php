<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    protected array $supported = ['en', 'ms', 'id', 'th', 'vi', 'km', 'lo', 'my', 'tl'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale', session('locale', 'en'));

        if (in_array($locale, $this->supported)) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
