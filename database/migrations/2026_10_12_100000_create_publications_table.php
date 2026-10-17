<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('publications')) {
            Schema::create('publications', function (Blueprint $t) {
                $t->id();
                $t->string('edition', 50);                 // "15th" — big number on cover
                $t->string('slug')->unique();              // /publication/{slug}
                $t->string('kind', 20)->default('bulletin'); // bulletin|magazine
                $t->string('title');
                $t->string('theme')->nullable();
                $t->string('year', 10)->nullable();
                $t->string('size', 50)->nullable();        // e.g. "373 MB"
                $t->text('summary')->nullable();           // optional own description (else shared CMS text)
                $t->string('color')->default('from-primary to-primary/70');
                $t->string('cover_path')->nullable();      // relative to /public or full URL
                $t->string('pdf_path')->nullable();        // relative to /public or full URL
                $t->unsignedInteger('sort_order')->default(0);
                $t->boolean('is_published')->default(true);
                $t->timestamps();
            });
        }

        if (DB::table('publications')->count() > 0) {
            return;
        }

        $b = 'media/bulletin/bulletin/';
        // edition, kind, year, theme, color, pdf, size  (listed newest first = index order)
        $rows = [
            ['15th', 'magazine', '2026', 'ASEANAPOL 45 — From Five Nations to Eleven Nations',     'from-amber-700 to-amber-900',   $b . 'aseanapol-magazine-15-2026-final.pdf', '373 MB'],
            ['14th', 'bulletin', '2025', '',                                                       'from-cyan-700 to-cyan-900',     $b . 'aseanapol-bulletin-14-2025.pdf', null],
            ['13th', 'magazine', '2023', 'Toward a Safer ASEAN: Cooperation, Innovation, Impact', 'from-amber-700 to-amber-900',   $b . 'aseanapol-bulletin-13.pdf', null],
            ['12th', 'bulletin', '2019', 'ASEANAPOL: Four Decades of Cooperation',                'from-primary/90 to-primary',    $b . 'aseanapol-bulletin-12.pdf', null],
            ['11th', 'bulletin', '2018', 'Building Resilient Police Forces',                      'from-teal-700 to-teal-900',     $b . 'aseanapol-bulletin-2018.pdf', null],
            ['10th', 'bulletin', '2017', 'Strengthening Partnerships for Regional Security',      'from-indigo-700 to-indigo-900', $b . 'aseanapol-bulletin-10.pdf', null],
            ['9th',  'bulletin', '2016', 'Combating Transnational Crime Together',                'from-primary to-primary/70',    $b . 'aseanapol-bulletin-9.pdf', null],
            ['8th',  'bulletin', '2015', 'Regional Police Cooperation in the New Millennium',     'from-blue-700 to-blue-900',     $b . 'aseanapol-bulletin-8.pdf', null],
        ];

        $now = now();
        foreach ($rows as $i => [$ed, $kind, $year, $theme, $color, $pdf, $size]) {
            $type = ucfirst($kind);
            DB::table('publications')->insert([
                'edition'      => $ed,
                'slug'         => "{$ed}-edition-aseanapol-{$kind}",
                'kind'         => $kind,
                'title'        => "{$ed} Edition ASEANAPOL {$type}",
                'theme'        => $theme !== '' ? $theme : null,
                'year'         => $year,
                'size'         => $size,
                'summary'      => null,
                'color'        => $color,
                'cover_path'   => null,
                'pdf_path'     => $pdf,
                'sort_order'   => ($i + 1) * 10,
                'is_published' => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
