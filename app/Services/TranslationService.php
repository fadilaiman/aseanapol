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
