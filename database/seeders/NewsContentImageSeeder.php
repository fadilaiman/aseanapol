<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsContentImageSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('docs/aseanapol/exports/media_content.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("media_content.csv not found.");
            return;
        }

        // Build GUID → public URL lookup
        $this->command->info('Building media lookup…');
        $lookup = [];
        $fh = fopen($csvPath, 'r');
        fgetcsv($fh, 0, '|'); // header row
        fgetcsv($fh, 0, '|'); // dashes separator
        while (($row = fgetcsv($fh, 0, '|')) !== false) {
            if (empty($row[0]) || empty($row[2])) continue;
            $guid     = strtolower(trim($row[0]));
            $filePath = trim($row[2]);
            $library  = strtolower(trim($row[10] ?? ''));
            if ($library && $filePath) {
                $lookup[$guid] = '/media/' . $library . '/' . $filePath;
            }
        }
        fclose($fh);
        $this->command->info('Loaded ' . count($lookup) . ' media entries.');

        $updated = 0;
        $skipped = 0;

        DB::table('news_items')
            ->where('content', 'like', '%[images|OpenAccessDataProvider]%')
            ->orderBy('id')
            ->chunk(50, function ($items) use ($lookup, &$updated, &$skipped) {
                foreach ($items as $item) {
                    $content = $item->content;

                    // Pass 1: Replace [images|...] when it's the src value of an existing <img> tag
                    // e.g. <img title="foo" src="[images|...]guid"> → <img src="/media/..." class="...">
                    $new = preg_replace_callback(
                        '/<img\s[^>]*src="\[images\|OpenAccessDataProvider\]([a-f0-9\-]{36})"[^>]*>/i',
                        function ($m) use ($lookup) {
                            $guid = strtolower($m[1]);
                            if (!isset($lookup[$guid])) {
                                return ''; // remove unresolvable img tags
                            }
                            $url = $lookup[$guid];
                            return '<img src="' . $url . '" class="rounded-xl shadow-md my-4 max-w-full">';
                        },
                        $content
                    );

                    // Pass 2: Replace standalone [images|...] refs with full <img> tags
                    $new = preg_replace_callback(
                        '/\[images\|OpenAccessDataProvider\]([a-f0-9\-]{36})/i',
                        function ($m) use ($lookup) {
                            $guid = strtolower($m[1]);
                            if (!isset($lookup[$guid])) {
                                return ''; // remove unresolvable refs
                            }
                            $url = $lookup[$guid];
                            return '<img src="' . $url . '" class="rounded-xl shadow-md my-4 max-w-full">';
                        },
                        $new
                    );

                    if ($new !== $content) {
                        DB::table('news_items')
                            ->where('id', $item->id)
                            ->update(['content' => $new]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                }
            });

        $this->command->info("Done — content updated: {$updated}, unchanged: {$skipped}");
    }
}
