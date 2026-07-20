<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Client for the translation gateway on the GPU box. Used only from queued
 * jobs / artisan commands — never from the request path.
 *
 * HTML is translated by walking text nodes: markup never leaves the app, so
 * the model can't corrupt tags or attributes.
 */
class GatewayTranslator
{
    public function isConfigured(): bool
    {
        return (bool) config('services.translator.url');
    }

    /** Translate a list of plain-text strings. Preserves order and count. */
    public function translateBatch(array $texts, string $targetLocale, string $sourceLocale = 'en'): array
    {
        if ($texts === [] || $targetLocale === $sourceLocale) {
            return $texts;
        }

        $response = Http::withToken(config('services.translator.token'))
            ->timeout((int) config('services.translator.timeout', 600))
            ->connectTimeout(10)
            ->post(rtrim(config('services.translator.url'), '/') . '/translate/batch', [
                'items'       => array_values($texts),
                'source_lang' => $sourceLocale,
                'target_lang' => $targetLocale,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException("translation gateway error {$response->status()}: " . mb_substr(mb_scrub($response->body(), 'UTF-8'), 0, 300));
        }

        $results = $response->json('results');

        if (! is_array($results) || count($results) !== count($texts)) {
            throw new RuntimeException('translation gateway returned mismatched batch');
        }

        return $results;
    }

    public function translate(string $text, string $targetLocale, string $sourceLocale = 'en'): string
    {
        return $this->translateBatch([$text], $targetLocale, $sourceLocale)[0];
    }

    /**
     * Translate an HTML fragment. Splits into tags and text nodes, sends only
     * the text nodes (skipping script/style/code/pre and translate="no"
     * subtrees) to the gateway in a single batch, then reassembles.
     */
    public function translateHtml(string $html, string $targetLocale, string $sourceLocale = 'en'): string
    {
        if (trim($html) === '' || $targetLocale === $sourceLocale) {
            return $html;
        }

        $skipTags     = 'script|style|code|pre|noscript|textarea';
        $skipDepth    = 0;
        $noTransStack = [];
        $parts        = preg_split('/(<[^>]*>)/s', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        // First pass: find translatable text nodes.
        $targets = []; // part index => trimmed source text
        foreach ($parts as $i => $part) {
            if (preg_match('/^<(' . $skipTags . ')[\s>\/]/i', $part)) {
                $skipDepth++;
                continue;
            }
            if (preg_match('/^<\/(' . $skipTags . ')>/i', $part)) {
                if ($skipDepth > 0) $skipDepth--;
                continue;
            }
            if ($skipDepth > 0) {
                continue;
            }
            if (str_starts_with($part, '<')) {
                if (preg_match('/^<([a-zA-Z][a-zA-Z0-9]*)[^>]*\btranslate\s*=\s*"no"/i', $part, $m)) {
                    array_push($noTransStack, strtolower($m[1]));
                } elseif (! empty($noTransStack) && preg_match('/^<\/([a-zA-Z][a-zA-Z0-9]*)\s*>/i', $part, $m)) {
                    if (strtolower($m[1]) === end($noTransStack)) {
                        array_pop($noTransStack);
                    }
                }
                continue;
            }
            if (! empty($noTransStack)) {
                continue;
            }

            $trimmed = trim(html_entity_decode($part, ENT_QUOTES | ENT_HTML5));
            if (mb_strlen($trimmed) > 1 && preg_match('/[a-zA-Z]/', $trimmed)) {
                $targets[$i] = $trimmed;
            }
        }

        if ($targets === []) {
            return $html;
        }

        $translated = $this->translateBatch(array_values($targets), $targetLocale, $sourceLocale);
        $byIndex    = array_combine(array_keys($targets), $translated);

        // Second pass: substitute, preserving surrounding whitespace.
        foreach ($byIndex as $i => $text) {
            preg_match('/^(\s*).*?(\s*)$/su', $parts[$i], $m);
            $parts[$i] = ($m[1] ?? '') . htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8', false) . ($m[2] ?? '');
        }

        return implode('', $parts);
    }
}
