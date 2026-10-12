<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Small helpers for the "site.*" CMS pages (header/footer/landing/contact).
 *
 * - t():    CMS value, but non-English locales keep their lang-file translation until an admin overrides the text.
 * - rows(): same idea for repeaters whose default rows were translated via lang files.
 * - link(): a CMS link value is either a route name ("about.index"), an absolute URL, or a path ("/en/...", "#").
 */
class SiteCms
{
    public static function t(string $page, string $path, ?string $langKey = null, bool $decode = false): mixed
    {
        $value = Cms::get($page, $path);
        if ($langKey && app()->getLocale() !== 'en') {
            $def = Cms::fieldDef($page, $path)['default'] ?? null;
            if ($value === $def) {
                $value = __($langKey);
            }
        }

        return ($decode && is_string($value)) ? html_entity_decode($value, ENT_QUOTES | ENT_HTML5) : $value;
    }

    /**
     * Repeater rows; for a non-English locale whose rows are still the unmodified defaults,
     * fields listed in $langMap ([field => [langKey per row index]]) are replaced by lang-file text.
     */
    public static function rows(string $page, string $path, array $langMap = []): array
    {
        $rows = Cms::get($page, $path) ?? [];
        if ($langMap && app()->getLocale() !== 'en') {
            $def = Cms::fieldDef($page, $path)['default'] ?? [];
            if ($rows === $def) {
                foreach ($rows as $i => $row) {
                    foreach ($langMap as $field => $keys) {
                        if (isset($keys[$i])) {
                            $rows[$i][$field] = __($keys[$i]);
                        }
                    }
                }
            }
        }

        return $rows;
    }

    /** True when every given field still equals its registry default (no admin override). */
    public static function isDefault(string $page, array $paths): bool
    {
        foreach ($paths as $p) {
            if (Cms::get($page, $p) !== (Cms::fieldDef($page, $p)['default'] ?? null)) {
                return false;
            }
        }

        return true;
    }

    // ------------------------------------------------------------------ shared-fact helpers

    private const NUMBER_WORDS = [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven',
        8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
        15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty'];

    /** Number of visible member forces (hidden rows excluded). */
    public static function memberCount(): int
    {
        return count(Shared::members());
    }

    /** Member count in words: the stored word while it still matches the stored number, else derived from the live count. */
    public static function memberCountWord(): string
    {
        $n = self::memberCount();
        $stored = (int) Shared::org('counts.member_count_num', 0);
        $word = trim((string) Shared::org('counts.member_count_word', ''));
        if ($word !== '' && $stored === $n) {
            return $word;
        }

        return self::NUMBER_WORDS[$n] ?? (string) $n;
    }

    /**
     * Fill {count_word} / {Count_word} / {count_num} in a sentence template with the live member count.
     * Text without placeholders (admin-edited prose, lang-file translations) passes through untouched.
     */
    public static function tpl(?string $text): string
    {
        $text = (string) $text;
        if (!str_contains($text, '{')) {
            return $text;
        }
        $word = self::memberCountWord();
        $k = (array) (Shared::all('organisation')['key_dates'] ?? []);
        $fc = explode(',', (string) ($k['first_conference_label'] ?? ''), 2);

        return strtr($text, [
            '{count_word}' => $word,
            '{Count_word}' => ucfirst($word),
            '{count_num}'  => (string) self::memberCount(),
            // organisation key dates (Shared facts > Organisation)
            '{founded_year}'            => (string) ($k['founded_year'] ?? ''),
            '{founded_date}'            => (string) ($k['founded_date_label'] ?? ''),
            '{first_conference_date}'   => trim($fc[0]),
            '{first_conference_place}'  => trim($fc[1] ?? ''),
            '{secretariat_established}' => (string) ($k['secretariat_established_label'] ?? ''),
            '{conference_count}'        => (string) ($k['conference_count_label'] ?? ''),
        ]);
    }

    /** "https://x.org/" -> "https://x.org" (shared websites carry a trailing slash on bare hosts; the site never showed it). */
    public static function bareUrl(?string $url): string
    {
        return (string) preg_replace('#^(https?://[^/]+)/$#i', '$1', trim((string) $url));
    }

    /**
     * A local list of partner references ([partner => key, ...]) joined with the shared partner facts.
     * Each result row: name (short), full, icon, url (bare, '' when none), plus the original local fields.
     * Rows pointing to an unknown/hidden partner are skipped.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function partnerRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $r) {
            $p = Shared::partner((string) ($r['partner'] ?? ''));
            if (!$p) {
                continue;
            }
            $out[] = $r + [
                'name' => $p['short'], 'full' => $p['full_name'] ?? '', 'icon' => $p['icon'] ?? '',
                'url' => self::bareUrl($p['website'] ?? ''),
            ];
        }

        return $out;
    }

    public static function link(?string $value): string
    {
        $v = trim((string) $value);
        if ($v === '') {
            return '#';
        }
        if (preg_match('#^([a-z][a-z0-9+.-]*):#i', preg_replace('/[\x00-\x20]+/', '', $v), $m)) {
            // only web/mail/phone schemes; javascript:, data:, vbscript: … degrade to a dead link
            return in_array(strtolower($m[1]), ['http', 'https', 'mailto', 'tel'], true) ? $v : '#';
        }
        if (preg_match('#^(//|/|\#)#', $v)) {
            return $v;
        }
        if (Route::has($v)) {
            try {
                return route($v, ['locale' => app()->getLocale()]);
            } catch (\Throwable) {
            }
        }

        return '/' . ltrim($v, '/');
    }

    /** Logo / flag file name or URL -> public src. */
    public static function logo(string $dir, ?string $value): string
    {
        $v = trim((string) $value);
        if ($v === '' || preg_match('#^(https?:)?//#i', $v) || str_starts_with($v, '/')) {
            return $v;
        }

        return $dir . $v;
    }
}
