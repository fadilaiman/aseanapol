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
