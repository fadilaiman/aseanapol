<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacebookNewsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('docs/aseanapol/exports/facebook_posts.json');

        if (!file_exists($jsonPath)) {
            $this->command->error('facebook_posts.json not found.');
            $this->command->line('Run: cd scripts && npm install && node scrape-facebook.mjs');
            return;
        }

        $posts = json_decode(file_get_contents($jsonPath), true);

        if (empty($posts)) {
            $this->command->error('No posts in facebook_posts.json.');
            return;
        }

        $now      = now()->toDateTimeString();
        $inserted = 0;
        $skipped  = 0;

        foreach ($posts as $post) {
            $slug = $this->makeSlug($post['title'] ?? '', $post['published_at'] ?? null);

            if (DB::table('news_items')->where('slug', $slug)->exists()) {
                $skipped++;
                continue;
            }

            DB::table('news_items')->insert([
                'id'           => Str::uuid()->toString(),
                'title'        => $post['title']        ?? 'ASEANAPOL Update',
                'summary'      => $post['summary']      ?? null,
                'content'      => $this->buildContent($post),
                'thumbnail'    => $post['thumbnail']    ?? null,
                'author'       => $post['author']       ?? 'ASEANAPOL',
                'published_at' => $post['published_at'] ?? null,
                'slug'         => $slug,
                'views_count'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            $inserted++;
        }

        $this->command->info("Inserted: {$inserted} | Skipped (duplicate): {$skipped}");
    }

    private function makeSlug(string $title, ?string $date): string
    {
        $prefix = $date ? substr($date, 0, 10) : date('Y-m-d');
        $base   = $prefix . '-' . Str::slug(mb_substr($title, 0, 60));
        $slug   = $base;
        $i      = 1;

        while (DB::table('news_items')->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function buildContent(array $post): ?string
    {
        $text = $post['content'] ?? null;
        if (!$text) return null;

        // Convert plain text to simple HTML paragraphs
        $paragraphs = array_filter(array_map('trim', explode("\n\n", $text)));
        $html = implode("\n", array_map(
            fn($p) => '<p>' . nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8')) . '</p>',
            $paragraphs
        ));

        // Append extra images (beyond thumbnail) as an image gallery
        $extras = array_slice($post['images'] ?? [], 1);
        if (!empty($extras)) {
            $html .= "\n\n<!-- gallery -->\n";
            foreach ($extras as $img) {
                $html .= '<img src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="" class="news-gallery-img">' . "\n";
            }
        }

        return $html;
    }
}
