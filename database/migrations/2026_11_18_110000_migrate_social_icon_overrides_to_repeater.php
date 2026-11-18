<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * site.header-footer "social" changed from 15 fixed fields (facebook_url, facebook_footer, ...) to a repeater
 * (social.links). Carry any admin-edited old values over so nothing is lost. Idempotent; no-op when nothing was edited.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cms_blocks')) {
            return;
        }
        $page = 'site.header-footer';
        $old = DB::table('cms_blocks')->where('page_key', $page)->where('block_key', 'like', 'social.%\_%')->get()
            ->filter(fn ($r) => preg_match('/^social\.(facebook|x|linkedin|instagram|youtube)_(url|footer|sidebar)$/', $r->block_key));
        if ($old->isEmpty() || DB::table('cms_blocks')->where('page_key', $page)->where('block_key', 'social.links')->exists()) {
            return;
        }
        $vals = [];
        foreach ($old as $r) {
            $vals[$r->block_key] = json_decode((string) $r->value, true);
        }
        $defaults = [
            'x' => 'https://twitter.com/ASEANAPOL1981', 'instagram' => 'https://www.instagram.com/aseanapol',
            'facebook' => 'https://www.facebook.com/ASEANAPOL', 'linkedin' => 'https://www.linkedin.com/in/aseanapol-secretariat-344498ab', 'youtube' => '',
        ];
        $rows = [];
        foreach ($defaults as $net => $url) {
            $rows[] = [
                'network' => $net, 'label' => '',
                'url' => (string) ($vals["social.{$net}_url"] ?? $url),
                'show_footer' => (bool) ($vals["social.{$net}_footer"] ?? true),
                'show_sidebar' => (bool) ($vals["social.{$net}_sidebar"] ?? true),
            ];
        }
        DB::table('cms_blocks')->insert([
            'page_key' => $page, 'block_key' => 'social.links',
            'value' => json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('cms_blocks')->where('page_key', $page)->whereIn('block_key', array_keys($vals))->delete();
    }

    public function down(): void
    {
    }
};
