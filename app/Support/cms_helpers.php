<?php

use App\Support\Cms;

if (!function_exists('cms')) {
    /** cms('about.vision-mission', 'hero.title') -> override or registry default. */
    function cms(string $page, string $path, mixed $default = null): mixed
    {
        return Cms::get($page, $path, $default);
    }
}

if (!function_exists('cms_on')) {
    /** Is the toggleable section visible? */
    function cms_on(string $page, string $section): bool
    {
        return Cms::visible($page, $section);
    }
}

if (!function_exists('cms_img')) {
    /** Public URL for an image/file field ('' when empty). */
    function cms_img(string $page, string $path): string
    {
        return Cms::url((string) Cms::get($page, $path, ''));
    }
}

if (!function_exists('cms_url')) {
    function cms_url(?string $path): string
    {
        return Cms::url($path);
    }
}

if (!function_exists('css_url')) {
    /**
     * Make a URL safe inside a CSS url('...') that sits in an HTML attribute. Blade's e() is not enough:
     * the browser decodes &#039; back to ' BEFORE the CSS parser runs, so a quote would close the CSS string
     * (CSS injection). Percent-encode the characters that can end/alter the CSS token.
     */
    function css_url(?string $url): string
    {
        return strtr((string) $url, ["'" => '%27', '"' => '%22', '(' => '%28', ')' => '%29', '\\' => '%5C', ' ' => '%20',
            "\n" => '', "\r" => '', "\t" => '', ';' => '%3B', '<' => '%3C', '>' => '%3E']);
    }
}

if (!function_exists('cms_html')) {
    /** Rich-text field, sanitized at save time; defaults are trusted code. */
    function cms_html(string $page, string $path): \Illuminate\Support\HtmlString
    {
        $html = (string) Cms::get($page, $path, '');
        // Defaults are trusted code; an admin override was sanitized at save time, but re-clean it on output
        // (defence in depth: a row written by an import, a bad migration or direct SQL must not become stored XSS).
        if ($html !== '' && $html !== (string) (Cms::fieldDef($page, $path)['default'] ?? '')) {
            $html = \App\Support\HtmlSanitizer::clean($html);
        }

        return new \Illuminate\Support\HtmlString($html);
    }
}

if (!function_exists('shared')) {
    /**
     * shared('secretariat', 'contact.phone') -> value of registry page shared.secretariat (override or default).
     * shared('members') (no path) -> every field of that page as [section => [field => value]].
     * For row sets prefer App\Support\Shared::members()/roster()/partners().
     */
    function shared(string $page, ?string $path = null, mixed $default = null): mixed
    {
        return $path === null ? \App\Support\Shared::all($page) : \App\Support\Shared::get($page, $path, $default);
    }
}

if (!function_exists('ini_to_bytes')) {
    /** "8M" / "2G" / "512K" / "1024" -> bytes (0 or -1 = unlimited -> PHP_INT_MAX). */
    function ini_to_bytes(string|false $v): int
    {
        $v = trim((string) $v);
        if ($v === '' || $v === '0' || $v === '-1') {
            return PHP_INT_MAX;
        }
        $n = (float) $v;
        switch (strtolower(substr($v, -1))) {
            case 'g': $n *= 1024;
            // no break
            case 'm': $n *= 1024;
            // no break
            case 'k': $n *= 1024;
        }

        return (int) $n;
    }
}

if (!function_exists('upload_limit_bytes')) {
    /** The real per-file upload limit: the form's own limit ($configMb) capped by PHP upload_max_filesize and post_max_size. */
    function upload_limit_bytes(int $configMb): int
    {
        return min($configMb * 1048576, ini_to_bytes(ini_get('upload_max_filesize')), ini_to_bytes(ini_get('post_max_size')));
    }
}

if (!function_exists('upload_limit_label')) {
    /** "25 MB" / "2 MB" label computed at render time from the effective limit. */
    function upload_limit_label(int $configMb): string
    {
        $mb = upload_limit_bytes($configMb) / 1048576;

        return (floor($mb) == $mb ? (string) (int) $mb : rtrim(rtrim(number_format($mb, 1), '0'), '.')) . ' MB';
    }
}
