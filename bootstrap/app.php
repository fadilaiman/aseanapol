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
            'admin.ip' => \App\Http\Middleware\AdminIpAllow::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin/*') && ! $request->expectsJson()) {
                $msg = e('The upload was too large for the server (limit ' . ini_get('post_max_size') . ' per request, '
                    . ini_get('upload_max_filesize') . ' per file). Use smaller or fewer files and try again.');
                return response('<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Upload too large</title>'
                    . '<body style="font-family:system-ui,sans-serif;max-width:560px;margin:15vh auto;padding:0 16px">'
                    . '<div role="alert" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:16px;border-radius:8px"><strong>Upload too large.</strong><br>' . $msg . '</div>'
                    . '<p><a href="javascript:history.back()">&larr; Go back to the form</a></p></body>', 413);
            }
        });
    })->create();
