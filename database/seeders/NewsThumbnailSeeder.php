<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsThumbnailSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('docs/aseanapol/exports/media_content.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("media_content.csv not found.");
            return;
        }

        // Build GUID → URL lookup from CSV
        // file_path in CSV is relative to the library folder, e.g. "news/as1.jpg"
        // Actual URL = /media/{library_folder}/{file_path}
        $this->command->info('Building media lookup…');
        $lookup = [];
        $fh = fopen($csvPath, 'r');
        fgetcsv($fh, 0, '|'); // header
        fgetcsv($fh, 0, '|'); // separator line
        while (($row = fgetcsv($fh, 0, '|')) !== false) {
            if (empty($row[0]) || empty($row[2])) continue;
            $guid      = strtolower(trim($row[0]));   // content_id
            $filePath  = trim($row[2]);                // file_path  e.g. "news/cobra.png"
            $library   = strtolower(trim($row[10] ?? '')); // library_name e.g. "News" → "news"
            if ($library && $filePath) {
                $lookup[$guid] = 'media/' . $library . '/' . $filePath;
            }
        }
        fclose($fh);
        $this->command->info('Loaded ' . count($lookup) . ' media entries.');

        // Iterate news items and resolve first image GUID
        $updated = 0;
        $notFound = 0;
        $noImage = 0;

        DB::table('news_items')
            ->whereNull('thumbnail')
            ->orderBy('published_at', 'desc')
            ->chunk(100, function ($items) use ($lookup, &$updated, &$notFound, &$noImage) {
                foreach ($items as $item) {
                    if (empty($item->content)) { $noImage++; continue; }

                    preg_match('/\[images\|OpenAccessDataProvider\]([a-f0-9\-]{36})/i', $item->content, $m);
                    if (empty($m[1])) { $noImage++; continue; }

                    $guid = strtolower($m[1]);
                    if (!isset($lookup[$guid])) { $notFound++; continue; }

                    DB::table('news_items')
                        ->where('id', $item->id)
                        ->update(['thumbnail' => $lookup[$guid]]);
                    $updated++;
                }
            });

        $this->command->info("Done — thumbnails set: {$updated}, GUID not in CSV: {$notFound}, no image: {$noImage}");
    }
}
