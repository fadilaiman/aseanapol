<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('news_items', 'sponsors')) {
            Schema::table('news_items', function (Blueprint $table) {
                $table->json('sponsors')->nullable();
            });
        }

        // Reproduce the old title-matching hack in data: the Anti-Scam Study Visit
        // articles showed the JAIF + Japan logos on the news list.
        $sponsors = json_encode([
            'label' => null,
            'items' => [
                ['name' => 'Japan-ASEAN Cooperation (JAIF)', 'logo' => 'media/logos/jaif-cooperation.png', 'url' => null],
                ['name' => 'Japan', 'logo' => 'media/logos/japan-flag.png', 'url' => null],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $rows = DB::table('news_items')->whereNull('sponsors')
            ->where('title', 'like', 'ASEANAPOL Anti-Scam Study Visit%')->get(['id', 'title']);
        foreach ($rows as $row) {
            if (str_starts_with($row->title, 'ASEANAPOL Anti-Scam Study Visit')) {
                DB::table('news_items')->where('id', $row->id)->whereNull('sponsors')->update(['sponsors' => $sponsors]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('news_items', 'sponsors')) {
            Schema::table('news_items', function (Blueprint $table) {
                $table->dropColumn('sponsors');
            });
        }
    }
};
