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

        // First pass: find translatable text nodes. Each node is split into
        // a leading/trailing boundary (whitespace AND punctuation, e.g. the
        // ", " right after a closing </strong> before the next clause) and
        // a translatable core. Only the core is sent to the model — models
        // routinely drop odd leading punctuation from an isolated fragment,
        // which previously glued adjacent translated segments together with
        // no separator at all (e.g. "Kittithiraphong</strong>held a...").
        // Preserving the exact original boundary characters verbatim avoids
        // that regardless of what the model does with the fragment.
        $targets = []; // part index => [core, prefix, suffix]
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

            $decoded = html_entity_decode($part, ENT_QUOTES | ENT_HTML5);
            preg_match('/^([^\p{L}\p{N}]*)(.*?)([^\p{L}\p{N}]*)$/su', $decoded, $m);
            [, $prefix, $core, $suffix] = $m;

            if (mb_strlen($core) > 1 && preg_match('/[a-zA-Z]/', $core)) {
                $targets[$i] = ['core' => $core, 'prefix' => $prefix, 'suffix' => $suffix];
            }
        }

        if ($targets === []) {
            return $html;
        }

        $keys       = array_keys($targets);
        $translated = $this->translateBatch(array_map(fn ($t) => $t['core'], $targets), $targetLocale, $sourceLocale);

        // Second pass: substitute, reattaching the original boundary chars.
        foreach ($keys as $idx => $i) {
            $t = $targets[$i];
            $parts[$i] = $t['prefix'] . htmlspecialchars($translated[$idx], ENT_NOQUOTES, 'UTF-8', false) . $t['suffix'];
        }

        return implode('', $parts);
    }
}
