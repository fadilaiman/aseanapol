<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsItemSyncSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('docs/aseanapol/exports/news_full.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("JSON file not found: {$jsonPath}");
            return;
        }

        ini_set('memory_limit', '512M');
        $this->command->info('Loading JSON…');

        // SQL Server FOR JSON splits output at 2048-char boundaries with newlines.
        // We must join all lines into a single string before decoding.
        $lines = file($jsonPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $raw   = implode('', $lines);
        unset($lines);

        $articles = json_decode($raw, true);
        unset($raw);

        if (!$articles) {
            $this->command->error('Failed to decode JSON.');
            return;
        }

        $this->command->info('Found ' . count($articles) . ' articles in source.');

        // Load existing slugs from MySQL
        $existingSlugs = DB::table('news_items')->pluck('slug', 'slug')->all();
        $this->command->info('MySQL already has ' . count($existingSlugs) . ' articles.');

        $inserted = 0;
        $skipped  = 0;
        $batch    = [];

        foreach ($articles as $a) {
            $slug = trim($a['slug'] ?? '');
            if (!$slug || isset($existingSlugs[$slug])) {
                $skipped++;
                continue;
            }

            $pubDate = $a['published_at'] ?? null;
            if ($pubDate) {
                // Strip milliseconds
                $pubDate = preg_replace('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.\d+/', '$1', $pubDate);
            }

            $batch[] = [
                'id'           => strtolower(trim($a['id'])),
                'title'        => mb_substr(trim($a['title'] ?? ''), 0, 500),
                'summary'      => $a['summary'] ?: null,
                'content'      => $a['content'] ?: null,
                'author'       => $a['author'] ?: null,
                'published_at' => $pubDate ?: null,
                'slug'         => $slug,
                'views_count'  => (int)($a['views_count'] ?? 0),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            $existingSlugs[$slug] = $slug;
            $inserted++;

            if (count($batch) >= 50) {
                DB::table('news_items')->insertOrIgnore($batch);
                $batch = [];
            }
        }

        if ($batch) {
            DB::table('news_items')->insertOrIgnore($batch);
        }

        $this->command->info("Done — inserted: {$inserted}, skipped (existing): {$skipped}");
    }
}
