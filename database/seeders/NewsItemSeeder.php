<?php

namespace Database\Seeders;

use App\Models\NewsItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsItemSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('docs/aseanapol/exports/news_items.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV not found: {$csvPath}");
            return;
        }

        DB::table('news_items')->truncate();

        $lines  = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count     = 0;
        $batch     = [];
        $now       = now()->toDateTimeString();
        $skip      = 2; // skip header + markdown separator row
        $seenSlugs = [];

        foreach ($lines as $i => $line) {
            if ($i < $skip) {
                continue;
            }

            $parts = explode('|', $line);

            // Need at least 10 columns (the minimum without any extra pipes in content)
            if (count($parts) < 10) {
                continue;
            }

            // Fields from left  (positions are stable even with extra | in content)
            $id      = trim($parts[0]);
            $title   = trim($parts[1]);
            $summary = trim($parts[2]) ?: null;

            // Fields from right (stable regardless of extra | in content)
            $viewsCount  = (int) trim(array_pop($parts));
            $status      = trim(array_pop($parts));
            $urlName     = trim(array_pop($parts));
            $dateCreated = trim(array_pop($parts));
            $pubDate     = trim(array_pop($parts));
            $author      = trim(array_pop($parts)) ?: null;

            // Content is whatever remains between summary (index 3) and author
            $content = trim(implode('|', array_slice($parts, 3))) ?: null;

            // Skip separator rows and invalid/duplicate records
            if (str_starts_with($id, '--') || $urlName === '' || !preg_match('/^[0-9A-Fa-f-]{36}$/', $id)) {
                continue;
            }

            if (isset($seenSlugs[$urlName]) || isset($seenSlugs[$id])) {
                continue; // skip duplicate slugs or ids
            }
            $seenSlugs[$urlName] = true;
            $seenSlugs[$id]      = true;

            // Strip subsecond precision (e.g. 2025-07-04 08:03:45.493 → 2025-07-04 08:03:45)
            $publishedAt = preg_replace('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.\d+/', '$1', $pubDate ?: $dateCreated);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}/', $publishedAt)) {
                $publishedAt = null;
            }

            $batch[] = [
                'id'           => $id,
                'title'        => $title,
                'summary'      => $summary,
                'content'      => $content,
                'author'       => $author,
                'published_at' => $publishedAt,
                'slug'         => $this->asciiSlug($urlName),
                'views_count'  => $viewsCount,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            $count++;

            if (count($batch) >= 100) {
                DB::table('news_items')->insertOrIgnore($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('news_items')->insertOrIgnore($batch);
        }

        $this->command->info("Seeded {$count} news items.");
    }

    /**
     * Transliterate non-ASCII characters to their ASCII equivalents so slugs
     * are always plain ASCII and safe in any URL context.
     */
    private function asciiSlug(string $slug): string
    {
        if (function_exists('transliterator_transliterate')) {
            $slug = (string) transliterator_transliterate('Any-Latin; Latin-ASCII', $slug);
        } else {
            $slug = (string) (iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug) ?: $slug);
        }
        // Drop anything still outside printable ASCII
        return preg_replace('/[^\x20-\x7E]/', '', $slug);
    }
}
