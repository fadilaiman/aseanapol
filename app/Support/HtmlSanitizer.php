<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Allow-list HTML sanitizer for admin-entered rich text (no external dependency).
 */
class HtmlSanitizer
{
    private const ALLOWED = [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'blockquote' => [],
        'hr' => [], 'span' => [], 'div' => [], 'sup' => [], 'sub' => [],
        'a' => ['href', 'target', 'rel', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [],
        'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        // <body> wrapper (not a <div>): a stray "</div>" in the input must not close the wrapper and drop the rest.
        $doc->loadHTML('<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>', LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $doc->getElementsByTagName('body')->item(0);
        if (!$root) {
            return e(strip_tags($html));
        }

        self::normalizeQuillLists($doc);
        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    /**
     * Quill 2 serialises bullet lists as <ol><li data-list="bullet">…</li></ol> (plus an empty
     * <span class="ql-ui"> per item). Stripping data-* would turn every bullet list into a numbered one,
     * so convert them to real <ul> and drop the editor-only spans before the allow-list pass.
     */
    private static function normalizeQuillLists(DOMDocument $doc): void
    {
        foreach (iterator_to_array($doc->getElementsByTagName('span')) as $span) {
            if ($span instanceof DOMElement && str_contains((string) $span->getAttribute('class'), 'ql-ui') && $span->parentNode) {
                $span->parentNode->removeChild($span);
            }
        }
        foreach (iterator_to_array($doc->getElementsByTagName('ol')) as $ol) {
            $first = null;
            foreach ($ol->childNodes as $c) {
                if ($c instanceof DOMElement && strtolower($c->nodeName) === 'li') {
                    $first = $c;
                    break;
                }
            }
            if ($first && $first->getAttribute('data-list') === 'bullet' && $ol->parentNode) {
                $ul = $doc->createElement('ul');
                while ($ol->firstChild) {
                    $ul->appendChild($ol->firstChild);
                }
                $ol->parentNode->replaceChild($ul, $ol);
            }
        }
    }

    /** Tags removed together with their content in rich (deny-list) mode. */
    private const RICH_DENY = ['script', 'style', 'object', 'embed', 'applet', 'base', 'meta', 'link', 'form', 'input', 'button',
        'textarea', 'select', 'option', 'svg', 'math', 'noscript', 'template', 'frame', 'frameset', 'title', 'xmp', 'plaintext'];
    private const IFRAME_HOSTS = ['youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com',
        'player.vimeo.com', 'facebook.com', 'www.facebook.com', 'web.facebook.com'];

    /**
     * Permissive mode for legacy news article bodies (keeps class/style/data-*, tables, figures, whitelisted
     * video iframes) while dropping everything executable. DOM based, so it is not fooled by malformed markup.
     */
    public static function cleanRich(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        $doc = new DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        // <body> wrapper (not a <div>): a stray "</div>" in the input must not close the wrapper and drop the rest.
        $doc->loadHTML('<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>', LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        $root = $doc->getElementsByTagName('body')->item(0);
        if (!$root) {
            return e(strip_tags($html));
        }
        self::walkRich($root);
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    private static function walkRich(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (!$child instanceof DOMElement) {
                if (in_array($child->nodeType, [XML_COMMENT_NODE, XML_PI_NODE, XML_CDATA_SECTION_NODE], true)) {
                    $node->removeChild($child);
                }
                continue;
            }
            $tag = strtolower($child->tagName);
            if (in_array($tag, self::RICH_DENY, true)) {
                $node->removeChild($child);
                continue;
            }
            if ($tag === 'iframe') {
                $host = strtolower((string) parse_url(preg_replace('/[\x00-\x20]+/', '', $child->getAttribute('src')), PHP_URL_HOST));
                $scheme = strtolower((string) parse_url(preg_replace('/[\x00-\x20]+/', '', $child->getAttribute('src')), PHP_URL_SCHEME));
                if (!in_array($host, self::IFRAME_HOSTS, true) || !in_array($scheme, ['http', 'https'], true)) {
                    $node->removeChild($child);
                    continue;
                }
            }
            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->name);
                $val = $attr->value;
                if (str_starts_with($name, 'on') || in_array($name, ['srcdoc', 'formaction', 'action', 'ping', 'background', 'dynsrc', 'lowsrc', 'xlink:href'], true)) {
                    $child->removeAttribute($attr->name);
                } elseif ($name === 'style') {
                    if (preg_match('/url\s*\(|expression|javascript|vbscript|behaviou?r|binding|@import|\\\\|[<>]|position\s*:\s*(fixed|sticky)/i', $val)) {
                        $child->removeAttribute($attr->name);
                    }
                } elseif ($name === 'src' && $tag === 'img' && preg_match('#^data:image/(png|gif|jpe?g|webp);base64,[a-z0-9+/=\s]+$#i', $val)) {
                    // inline raster placeholder (never svg/html) is harmless
                } elseif (in_array($name, ['href', 'src', 'data', 'poster', 'cite', 'longdesc'], true) && !self::safeUrl($val, $name !== 'href')) {
                    $child->removeAttribute($attr->name);
                } elseif (in_array($name, ['srcset', 'data-srcset', 'data-src'], true)) {
                    foreach (preg_split('/\s*,\s*/', $val) as $cand) {
                        if (!self::safeUrl((string) preg_split('/\s+/', trim($cand))[0], true)) {
                            $child->removeAttribute($attr->name);
                            break;
                        }
                    }
                }
            }
            if ($tag === 'a' && strtolower($child->getAttribute('target')) === '_blank') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }
            self::walkRich($child);
        }
    }

    private static function walk(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (!$child instanceof DOMElement) {
                if (in_array($child->nodeType, [XML_COMMENT_NODE, XML_PI_NODE, XML_CDATA_SECTION_NODE], true)) {
                    $node->removeChild($child);
                }
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'link', 'meta', 'svg', 'math'], true)) {
                $node->removeChild($child);
                continue;
            }

            if (!isset(self::ALLOWED[$tag])) {
                // unwrap: keep children, drop the element
                self::walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->name);
                if (!in_array($name, self::ALLOWED[$tag], true)) {
                    $child->removeAttribute($attr->name);
                    continue;
                }
                if (in_array($name, ['href', 'src'], true) && !self::safeUrl($attr->value, $name === 'src')) {
                    $child->removeAttribute($attr->name);
                }
            }

            if ($tag === 'a' && $child->getAttribute('target') === '_blank') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }

            self::walk($child);
        }
    }

    private static function safeUrl(string $url, bool $isSrc): bool
    {
        $url = trim(preg_replace('/[\x00-\x20]+/', '', $url));
        if ($url === '' || str_starts_with($url, '/') || str_starts_with($url, '#') || str_starts_with($url, '?')) {
            return true;
        }
        $allowed = $isSrc ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'];
        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $url, $m)) {
            return in_array(strtolower($m[1]), $allowed, true);
        }

        return true; // relative path
    }
}
