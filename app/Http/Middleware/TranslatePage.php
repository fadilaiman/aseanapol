<?php

namespace App\Http\Middleware;

use App\Services\TranslationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TranslatePage
{
    /**
     * Translate all remaining English text nodes on the rendered page.
     *
     * • Only fires for non-English locales on HTML responses.
     * • Skips <script>, <style>, <code>, <pre> blocks.
     * • Only translates nodes that still contain Latin letters — content
     *   already translated via __() is left untouched.
     * • Caches the translated HTML permanently per URL + locale so the
     *   translation API is only called once per page per language.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $locale = app()->getLocale();

        if ($locale === 'en') {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $cacheKey = 'tpage_' . md5($locale . '|' . $request->path() . '|' . $request->getQueryString());

        $translated = Cache::rememberForever($cacheKey, function () use ($response, $locale) {
            return app(TranslationService::class)->translatePage($response->getContent(), $locale);
        });

        $response->setContent($translated);

        return $response;
    }
}
