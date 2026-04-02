<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TranslationService
{
    private string $cachePath;
    private array $cache = [];
    private bool $dirty = false;

    public function __construct()
    {
        $this->cachePath = storage_path('app/translation_cache.json');
        $this->loadCache();
    }

    public function translate(string $text, string $targetLocale, string $sourceLocale = 'en'): string
    {
        if ($sourceLocale === $targetLocale || trim($text) === '') {
            return $text;
        }

        $cacheKey = md5("{$sourceLocale}:{$targetLocale}:{$text}");

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        // Protect HTML tags and Laravel :placeholders from being translated
        $placeholders = [];
        $protected = preg_replace_callback(
            '/(<[^>]+>|:[a-zA-Z_][a-zA-Z0-9_]*)/',
            function ($m) use (&$placeholders) {
                $token = '__PH' . count($placeholders) . '__';
                $placeholders[$token] = $m[0];
                return $token;
            },
            $text
        );

        $result = $this->callApi($protected, $targetLocale, $sourceLocale);

        // Restore protected tokens
        if ($placeholders) {
            $result = str_replace(array_keys($placeholders), array_values($placeholders), $result);
        }

        $this->cache[$cacheKey] = $result;
        $this->dirty = true;

        return $result;
    }

    /**
     * Translate an HTML string, splitting at block-level boundaries so long
     * articles never exceed the API character limit.  Each chunk is cached
     * independently so re-renders are instant.
     */
    public function translateHtml(string $html, string $targetLocale, string $sourceLocale = 'en'): string
    {
        if ($sourceLocale === $targetLocale || trim($html) === '') {
            return $html;
        }

        // Short content → single request
        if (mb_strlen($html) < 3500) {
            return $this->translate($html, $targetLocale, $sourceLocale);
        }

        // Split BEFORE each block-level opening tag so every chunk is a
        // self-contained, balanced block (e.g. full <p>…</p>).
        $chunks = preg_split(
            '/(?=<(?:p|h[1-6]|li|ul|ol|blockquote|table|thead|tbody|tr|div|section|article)\b)/i',
            $html,
            flags: PREG_SPLIT_NO_EMPTY
        );

        return implode('', array_map(
            fn(string $c) => $this->translate(trim($c), $targetLocale, $sourceLocale),
            array_filter($chunks, fn($c) => trim($c) !== '')
        ));
    }

    /**
     * Translate a rendered HTML page by walking text nodes only.
     * Skips <script>, <style>, <code>, <pre> blocks.
     * Only translates nodes that still contain Latin characters
     * (so content already translated via __() is left untouched).
     * Whole-page result is NOT cached here — callers should cache it.
     */
    public function translatePage(string $html, string $targetLocale, string $sourceLocale = 'en'): string
    {
        if ($sourceLocale === $targetLocale) {
            return $html;
        }

        $skipTags  = 'script|style|code|pre|noscript|textarea';
        $skipDepth = 0;
        $parts     = preg_split('/(<[^>]*>)/s', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result    = '';

        foreach ($parts as $part) {
            // Opening skip tag
            if (preg_match('/^<(' . $skipTags . ')[\s>\/]/i', $part)) {
                $skipDepth++;
                $result .= $part;
                continue;
            }
            // Closing skip tag
            if (preg_match('/^<\/(' . $skipTags . ')>/i', $part)) {
                if ($skipDepth > 0) $skipDepth--;
                $result .= $part;
                continue;
            }
            // Inside skip block or it's a tag
            if ($skipDepth > 0 || str_starts_with($part, '<')) {
                $result .= $part;
                continue;
            }
            // Text node: only translate if it has Latin letters (still English)
            $trimmed = trim($part);
            if (mb_strlen($trimmed) > 2 && preg_match('/[a-zA-Z]{3,}/', $trimmed)) {
                $translated = $this->translate($trimmed, $targetLocale, $sourceLocale);
                // Preserve surrounding whitespace
                preg_match('/^(\s*)(.*?)(\s*)$/su', $part, $m);
                $result .= ($m[1] ?? '') . $translated . ($m[3] ?? '');
            } else {
                $result .= $part;
            }
        }

        return $result;
    }

    public function flush(): void
    {
        if ($this->dirty) {
            $this->saveCache();
            $this->dirty = false;
        }
    }

    public function __destruct()
    {
        $this->flush();
    }

    private function callApi(string $text, string $tl, string $sl): string
    {
        try {
            $response = Http::timeout(20)->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => $sl,
                'tl'     => $tl,
                'dt'     => 't',
                'q'      => $text,
            ]);

            if (! $response->successful()) {
                return $text;
            }

            $data   = $response->json();
            $result = '';

            foreach ($data[0] as $part) {
                $result .= $part[0] ?? '';
            }

            return $result ?: $text;
        } catch (\Throwable) {
            return $text;
        }
    }

    private function loadCache(): void
    {
        if (file_exists($this->cachePath)) {
            $this->cache = json_decode(file_get_contents($this->cachePath), true) ?? [];
        }
    }

    private function saveCache(): void
    {
        file_put_contents(
            $this->cachePath,
            json_encode($this->cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
