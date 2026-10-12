<?php

namespace Tests\Unit;

use App\Support\Cms;
use App\Support\HtmlSanitizer;
use Tests\TestCase;

class CmsCoreTest extends TestCase
{
    public function test_sanitizer_strips_scripts_handlers_and_bad_schemes(): void
    {
        $out = HtmlSanitizer::clean('<p onclick="x()">Hi</p><script>alert(1)</script><a href="javascript:alert(1)">l</a><img src="x" onerror="y()">');

        $this->assertStringNotContainsString('script', $out);
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringNotContainsString('onerror', $out);
        $this->assertStringNotContainsString('javascript:', $out);
        $this->assertStringContainsString('Hi', $out);
    }

    public function test_sanitizer_keeps_safe_markup(): void
    {
        $out = HtmlSanitizer::clean('<h2>T</h2><ul><li><strong>a</strong></li></ul><a href="https://example.org" target="_blank">x</a>');

        $this->assertStringContainsString('<h2>T</h2>', $out);
        $this->assertStringContainsString('<li><strong>a</strong></li>', $out);
        $this->assertStringContainsString('rel="noopener noreferrer"', $out);
    }

    public function test_normalize_handles_types(): void
    {
        $this->assertSame('x', Cms::normalize(['type' => 'text'], '  x ', null));
        $this->assertSame(['a', 'b'], Cms::normalize(['type' => 'list'], "a\r\n\r\nb", null));
        $this->assertSame(['مرحبا', 'ทดสอบ'], Cms::normalize(['type' => 'list'], "مرحبا\nทดสอบ", null));
        $this->assertSame('', Cms::normalize(['type' => 'url'], 'javascript:alert(1)', null));
        $this->assertSame('https://a.test', Cms::normalize(['type' => 'url'], 'https://a.test', null));
        $this->assertSame(5, Cms::normalize(['type' => 'number'], '5', null));
        $this->assertNull(Cms::normalize(['type' => 'number'], '1e999', null));
    }

    public function test_repeater_normalisation_drops_empty_rows_and_keeps_hide(): void
    {
        $def = ['type' => 'repeater', 'fields' => ['t' => ['type' => 'text']]];
        $rows = Cms::normalize($def, [
            '__present' => '1',
            0 => ['t' => 'one', '_hide' => '1'],
            1 => ['t' => ''],
            2 => ['t' => 'three'],
        ], null);

        $this->assertSame([['t' => 'one', '_hide' => true], ['t' => 'three']], $rows);
    }
}
