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

if (!function_exists('cms_html')) {
    /** Rich-text field, sanitized at save time; defaults are trusted code. */
    function cms_html(string $page, string $path): \Illuminate\Support\HtmlString
    {
        return new \Illuminate\Support\HtmlString((string) Cms::get($page, $path, ''));
    }
}
