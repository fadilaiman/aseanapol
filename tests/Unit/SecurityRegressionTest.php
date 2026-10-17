<?php

namespace Tests\Unit;

use App\Console\Commands\ReindexGallery;
use App\Support\HtmlSanitizer;
use Tests\TestCase;

/** Regression tests for the findings in resources/cms/SECURITY_PENTEST_REPORT.md. */
class SecurityRegressionTest extends TestCase
{
    public function test_css_url_cannot_break_out_of_a_css_string(): void
    {
        $out = css_url("a.png');background:url(//evil.test/x);('\\\n");

        $this->assertStringNotContainsString("'", $out);
        $this->assertStringNotContainsString('(', $out);
        $this->assertStringNotContainsString(')', $out);
        $this->assertStringNotContainsString(';', $out);
        $this->assertSame('http://x.test/media/a-b_c.jpg', css_url('http://x.test/media/a-b_c.jpg'));
    }

    public function test_gallery_reindex_rejects_hostile_paths(): void
    {
        $m = new \ReflectionMethod(ReindexGallery::class, 'normalizePath');
        $m->setAccessible(true);
        $cmd = new ReindexGallery();

        foreach (['media/../../.env.png', 'media/a" onerror="x.png', "media/a'b.png", 'media/a<b>.png', 'media/a\\b.png', 'media/a.png?x=1', 'media/a.png#x', "media/a\nb.png", 'storage/x.png', 'media/x.php'] as $bad) {
            $this->assertNull($m->invoke($cmd, $bad), "must reject: $bad");
        }
        $this->assertSame('media/news/news/ok-1.jpeg', $m->invoke($cmd, 'https://aseanapol.org/media/news/news/ok-1.jpeg'));
        $this->assertSame('media/news/news/WhatsApp Image (1).jpeg', $m->invoke($cmd, '/media/news/news/WhatsApp Image (1).jpeg'));
    }

    /** @dataProvider vectors */
    public function test_sanitizers_neutralise_xss_vectors(string $payload): void
    {
        foreach (['clean', 'cleanRich'] as $mode) {
            $out = HtmlSanitizer::$mode($payload);
            $this->assertDoesNotMatchRegularExpression('/<script|<svg|<iframe[^>]*srcdoc|<form|<object|<embed|<base|\son\w+\s*=|(?:href|src)\s*=\s*["\']?\s*(?:javascript|vbscript|data:text)/i', $out, "$mode leaked: $out");
        }
    }

    public static function vectors(): array
    {
        return array_map(fn ($v) => [$v], [
            '<img src=x onerror=alert(1)>', '<svg/onload=alert(1)>', '<math><mtext><table><mglyph><style><img src=x onerror=alert(1)>',
            '<a href="jav&#x09;ascript:alert(1)">x</a>', '<a href="&#106;avascript:alert(1)">x</a>', '<noscript><p title="</noscript><img src=x onerror=alert(1)>">',
            '<textarea><img src=x onerror=alert(1)></textarea>', '<xmp><img src=x onerror=alert(1)></xmp>', '<iframe srcdoc="<script>alert(1)</script>"></iframe>',
            '<form action=javascript:alert(1)><input type=submit></form>', '<a href="data:text/html,<script>alert(1)</script>">x</a>',
            '<!--><img src=x onerror=alert(1)>-->', '<base href="//evil.com/"><a href=a>x</a>', '<object data="javascript:alert(1)"></object>',
            '<select><template><img src=x onerror=alert(1)></template></select>', '<details open ontoggle=alert(1)>', '<body onload=alert(1)>',
        ]);
    }
}
