<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // nginx has no realip module, so REMOTE_ADDR is the connecting Cloudflare edge IP,
        // not 127.0.0.1 — trust Cloudflare's published ranges so X-Forwarded-For resolves
        // to the real visitor IP. Source: https://www.cloudflare.com/ips-v4 / ips-v6.
        $middleware->trustProxies(at: [
            '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
            '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
            '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
            '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
            '2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32',
            '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'admin.once' => \App\Http\Middleware\AdminDedupeSubmit::class,
            'admin.ip' => \App\Http\Middleware\AdminIpAllow::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Friendly, standalone notice pages for the two failures an admin can hit while a form is open.
        $notice = function (int $status, string $title, string $heading, string $body, bool $login) {
            return response()->view('errors.admin-notice', compact('title', 'heading', 'body', 'login'), $status);
        };

        // 413: the request body exceeded post_max_size / the web server body limit (nothing reached the app).
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) use ($notice) {
            if (! $request->expectsJson()) {
                $limit = upload_limit_label(500);
                $perReq = ini_get('post_max_size');

                return $notice(413, 'Upload too large', 'The upload was too large',
                    "The files you sent are bigger than the server accepts (about {$limit} per file, {$perReq} per request in total). Nothing was saved. Go back, choose smaller or fewer files, and save again.",
                    $request->is('admin/*'));
            }
        });

        // 419: the CSRF token / session expired while the form was open.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) use ($notice) {
            if ($e->getStatusCode() === 419 && ! $request->expectsJson()) {
                return $notice(419, 'Session expired', 'Your session expired - sign in again',
                    'For your security the session ended while this page was open, so what you just sent was NOT saved. Sign in again and repeat the change. '
                    . 'Tip: before leaving, press your browser Back button to see the form with your typed text still in it (browsers usually keep it) and copy it first.',
                    true);
            }
        });
    })->create();
