<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeds news_items from docs/facebook/scraped/ folder structure.
 *
 * Each sub-directory contains:
 *   post.txt  — metadata + full post body
 *   image_1.jpg … image_N.jpg  — downloaded images
 *
 * Images are copied to public/media/news/facebook/YYYY-MM/ and the
 * relative path (media/news/facebook/YYYY-MM/filename) is stored in
 * the thumbnail / content fields so asset() works as-is.
 *
 * Run: php artisan db:seed --class=ScrapedFacebookNewsSeeder
 */
class ScrapedFacebookNewsSeeder extends Seeder
{
    public function run(): void
    {
        $scrapedPath = base_path('docs/facebook/scraped');

        if (!is_dir($scrapedPath)) {
            $this->command->error("Scraped directory not found: {$scrapedPath}");
            return;
        }

        $folders = array_filter(
            scandir($scrapedPath),
            fn($name) => $name !== '.' && $name !== '..'
                      && !str_starts_with($name, '_')
                      && is_dir($scrapedPath . '/' . $name)
        );

        $inserted = 0;
        $skipped  = 0;
        $now      = now()->toDateTimeString();

        foreach ($folders as $folder) {
            $folderPath = $scrapedPath . '/' . $folder;
            $postFile   = $folderPath . '/post.txt';

            if (!file_exists($postFile)) {
                $this->command->warn("No post.txt in: {$folder}");
                continue;
            }

            // The folder name IS the slug (already URL-safe per SCRAPE_REFERENCE.md)
            $slug = $folder;

            if (DB::table('news_items')->where('slug', $slug)->exists()) {
                $skipped++;
                continue;
            }

            $txt   = file_get_contents($postFile);
            $lines = explode("\n", $txt);

            $sourceUrl  = trim(str_replace('URL:  ', '', $lines[0] ?? ''));
            $dateRaw    = trim(str_replace('Date: ', '', $lines[1] ?? ''));
            $body       = trim(Str::after($txt, "--- POST TEXT ---\n"));

            // Parse date from the YYYY-MM-DD folder name prefix
            $publishedAt = null;
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $folder, $dm)) {
                $publishedAt = $dm[1] . ' 08:00:00';
            }

            // Title = first non-empty line of body
            $bodyLines = explode("\n", $body);
            $title = '';
            foreach ($bodyLines as $bl) {
                $bl = trim($bl);
                if ($bl !== '') { $title = $bl; break; }
            }
            if (!$title) {
                $title = str_replace(['_', '-'], ' ', $folder);
            }

            // Summary = first paragraph (up to first blank line), stripped of social links
            $paragraphs   = array_values(array_filter(explode("\n\n", $body)));
            $rawSummary   = $paragraphs[0] ?? '';
            $summary      = mb_substr(preg_replace('/\s+/', ' ', strip_tags($rawSummary)), 0, 500) ?: null;

            // Copy images and collect relative public paths
            $imagePaths = $this->copyImages($folderPath, $folder, $publishedAt);

            $thumbnail = $imagePaths[0] ?? null;
            $content   = $this->buildContent($body, $imagePaths);

            DB::table('news_items')->insert([
                'id'           => Str::uuid()->toString(),
                'title'        => mb_substr(trim($title), 0, 255),
                'summary'      => $summary,
                'content'      => $content,
                'thumbnail'    => $thumbnail,
                'author'       => 'ASEANAPOL',
                'published_at' => $publishedAt,
                'slug'         => $slug,
                'views_count'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            $inserted++;
        }

        $this->command->info("Scraped Facebook posts — inserted: {$inserted} | skipped (duplicate): {$skipped}");
    }

    /**
     * Copy image files from the scraped folder to public/media/news/facebook/YYYY-MM/
     * and return their relative public paths (no leading slash).
     *
     * @return string[]
     */
    private function copyImages(string $folderPath, string $folder, ?string $publishedAt): array
    {
        $yearMonth = $publishedAt ? substr($publishedAt, 0, 7) : date('Y-m');
        $destDir   = public_path("media/news/facebook/{$yearMonth}");

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Collect image files sorted numerically (image_1.jpg, image_2.jpg …)
        $files = array_values(array_filter(
            scandir($folderPath),
            fn($f) => preg_match('/^image_\d+\.(jpg|jpeg|png|gif|webp)$/i', $f)
        ));

        usort($files, fn($a, $b) => (int) filter_var($a, FILTER_SANITIZE_NUMBER_INT)
                                  - (int) filter_var($b, FILTER_SANITIZE_NUMBER_INT));

        $paths = [];

        foreach ($files as $i => $filename) {
            $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $destName = substr($folder, 0, 30) . "-{$i}.{$ext}";
            $destFile = $destDir . '/' . $destName;

            if (!file_exists($destFile)) {
                copy($folderPath . '/' . $filename, $destFile);
            }

            $paths[] = "media/news/facebook/{$yearMonth}/{$destName}";
        }

        return $paths;
    }

    /**
     * Convert plain-text post body to HTML and embed gallery images.
     */
    private function buildContent(string $body, array $imagePaths): string
    {
        // Strip trailing social media links / hashtag block
        $body = preg_replace('/#ASEANAPOL[\s\S]*$/u', '', $body);
        $body = trim($body);

        // Convert paragraphs to <p> tags
        $paragraphs = array_filter(array_map('trim', explode("\n\n", $body)));
        $html = implode("\n", array_map(
            fn($p) => '<p>' . nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8')) . '</p>',
            $paragraphs
        ));

        // Gallery: images beyond the cover (index 0)
        $gallery = array_slice($imagePaths, 1);
        if (!empty($gallery)) {
            $html .= "\n\n<div class=\"news-gallery grid grid-cols-2 md:grid-cols-3 gap-3 mt-6\">\n";
            foreach ($gallery as $path) {
                $escaped = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
                $html .= '<img src="/' . $escaped . '" alt="" class="rounded-xl shadow-md w-full h-48 object-cover">' . "\n";
            }
            $html .= "</div>\n";
        }

        return $html;
    }
}
