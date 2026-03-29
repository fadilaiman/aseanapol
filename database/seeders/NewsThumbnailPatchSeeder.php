<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fills missing thumbnails for news articles using two strategies:
 *
 * Pass 1 — articles whose content still has raw [images|OpenAccessDataProvider]guid refs.
 *           Look the GUID up in media_content.csv to build the URL.
 *
 * Pass 2 — articles whose content was already processed by NewsContentImageSeeder
 *           and now contains <img src="/media/..."> tags.  Extract the src directly.
 */
class NewsThumbnailPatchSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('docs/aseanapol/exports/media_content.csv');

        // Build GUID → URL lookup (same logic as NewsThumbnailSeeder)
        $lookup = [];
        if (file_exists($csvPath)) {
            $this->command->info('Building media lookup from CSV…');
            $fh = fopen($csvPath, 'r');
            fgetcsv($fh, 0, '|'); // header
            fgetcsv($fh, 0, '|'); // separator
            while (($row = fgetcsv($fh, 0, '|')) !== false) {
                if (empty($row[0]) || empty($row[2])) continue;
                $guid    = strtolower(trim($row[0]));
                $file    = trim($row[2]);
                $library = strtolower(trim($row[10] ?? ''));
                if ($library && $file) {
                    $lookup[$guid] = 'media/' . $library . '/' . $file;
                }
            }
            fclose($fh);
            $this->command->info('Loaded ' . count($lookup) . ' media entries.');
        } else {
            $this->command->warn('media_content.csv not found — will skip GUID lookup (Pass 1).');
        }

        $pass1 = 0; // resolved via GUID lookup
        $pass2 = 0; // resolved via <img src> extraction
        $none  = 0; // no image found

        DB::table('news_items')
            ->whereNull('thumbnail')
            ->orderBy('published_at', 'desc')
            ->chunk(100, function ($items) use ($lookup, &$pass1, &$pass2, &$none) {
                foreach ($items as $item) {
                    if (empty($item->content)) { $none++; continue; }

                    // Pass 1: raw [images|OpenAccessDataProvider]guid ref
                    if (!empty($lookup)) {
                        preg_match('/\[images\|OpenAccessDataProvider\]([a-f0-9\-]{36})/i', $item->content, $m);
                        if (!empty($m[1])) {
                            $guid = strtolower($m[1]);
                            if (isset($lookup[$guid])) {
                                DB::table('news_items')->where('id', $item->id)
                                    ->update(['thumbnail' => $lookup[$guid]]);
                                $pass1++;
                                continue;
                            }
                        }
                    }

                    // Pass 2: already-resolved <img src="/media/..."> tag
                    preg_match('/<img\s[^>]*src="([^"]*\/media\/[^"]+)"[^>]*>/i', $item->content, $img);
                    if (!empty($img[1])) {
                        $src = ltrim($img[1], '/'); // strip leading slash → "media/news/..."
                        DB::table('news_items')->where('id', $item->id)
                            ->update(['thumbnail' => $src]);
                        $pass2++;
                        continue;
                    }

                    $none++;
                }
            });

        $this->command->info("Done — GUID lookup: {$pass1}, img extraction: {$pass2}, no image: {$none}");
    }
}
