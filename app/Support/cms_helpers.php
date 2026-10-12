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
