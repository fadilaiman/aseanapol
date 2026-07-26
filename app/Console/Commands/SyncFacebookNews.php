<?php

namespace App\Console\Commands;

use App\Models\FacebookPost;
use App\Models\NewsItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pulls newly scraped ASEANAPOL Facebook posts from the translator gateway
 * (which fronts the fb_scraper spool on the GPU box) and publishes them as
 * news items: verbatim caption paragraphs + image gallery, exactly the shape
 * the manual deploy_news_*.py scripts produced. Translations follow
 * automatically via translate:sync-news (hash diff).
 *
 * Failure mode per post is "leave pending + log"; a post is acked at the
 * gateway only after the news row and all images are in place.
 */
class SyncFacebookNews extends Command
{
    protected $signature = 'news:sync-facebook
        {--dry-run : Show what would be published without writing anything}';

    protected $description = 'Publish pending Facebook posts from the scraper gateway as news items';

    public function handle(): int
    {
        $base = rtrim((string) config('services.translator.url'), '/');
        if (! $base) {
            $this->warn('services.translator.url not configured — nothing to do.');
            return self::SUCCESS;
        }

        try {
            $resp = $this->http()->get("{$base}/facebook/pending");
        } catch (\Throwable $e) {
            $this->warn("Gateway unreachable — skipping this run ({$e->getMessage()}).");
            return self::SUCCESS;
        }

        if (! $resp->successful()) {
            $this->warn("Gateway /facebook/pending returned {$resp->status()} — skipping.");
            return self::SUCCESS;
        }

        $posts = $resp->json('posts') ?? [];
        $this->info(count($posts) . ' pending post(s).');

        $published = 0;
        foreach ($posts as $post) {
            try {
                $published += $this->processPost($base, $post) ? 1 : 0;
            } catch (\Throwable $e) {
                Log::error('facebook-sync: failed to process post', [
                    'post_id' => $post['post_id'] ?? '?',
                    'error' => $e->getMessage(),
                ]);
                $this->error("  FAILED {$post['post_id']}: {$e->getMessage()} (left pending, will retry)");
            }
        }

        if ($published > 0 && ! $this->option('dry-run')) {
            \Artisan::call('cache:clear');
            $this->info("Published {$published} article(s); cache cleared.");
        }

        return self::SUCCESS;
    }

    private function processPost(string $base, array $post): bool
    {
        $fbId = $post['post_id'];

        if (FacebookPost::where('fb_post_id', $fbId)->exists()) {
            $this->line("  already ingested: {$fbId} — acking.");
            $this->ack($base, $fbId, null);
            return false;
        }

        $article = $this->buildArticle($post);

        if (NewsItem::where('slug', $article['slug'])->exists()) {
            $this->warn("  slug exists ({$article['slug']}) — assuming manual duplicate, skipping + acking.");
            if (! $this->option('dry-run')) {
                FacebookPost::create([
                    'fb_post_id' => $fbId,
                    'permalink' => $post['permalink'] ?? null,
                    'status' => 'skipped',
                    'payload' => ['reason' => 'slug exists', 'slug' => $article['slug']],
                ]);
                $this->ack($base, $fbId, null);
            }
            return false;
        }

        $this->line("  new: [{$fbId}] {$article['title']}");
        $this->line("       slug={$article['slug']} images=" . count($post['images']) . " published_at={$article['published_at']}");

        if ($this->option('dry-run')) {
            $this->line('       (dry-run: not writing)');
            return false;
        }

        // 1. Images first — article must never go live with a broken gallery.
        $saved = $this->downloadImages($base, $fbId, $post['images'], $article['slug']);
        if ($saved === 0) {
            throw new \RuntimeException('no images could be downloaded');
        }

        // 2. News row.
        $id = (string) Str::uuid();
        NewsItem::create([
            'id' => $id,
            'title' => $article['title'],
            'summary' => $article['summary'],
            'content' => $article['content_body'] . "\n\n" . $this->galleryHtml($article['slug'], $saved),
            'author' => 'ASEANAPOL Secretariat',
            'published_at' => $article['published_at'],
            'slug' => $article['slug'],
            'thumbnail' => "media/news/news/{$article['slug']}-1.jpeg",
            'views_count' => 0,
        ]);

        // 3. Ledger + ack — only now is the post done.
        FacebookPost::create([
            'fb_post_id' => $fbId,
            'permalink' => $post['permalink'] ?? null,
            'status' => 'published',
            'news_item_id' => $id,
            'payload' => ['images' => $saved, 'date_source' => $post['date_source'] ?? null],
        ]);
        $this->ack($base, $fbId, $id);

        $this->info("       published: /en/news-media/news/{$article['slug']}");
        return true;
    }

    /**
     * Verbatim article from the caption: title = first line, body = the
     * remaining paragraphs as <p> tags, hashtag-only paragraphs dropped.
     */
    private function buildArticle(array $post): array
    {
        $text = trim($post['text']);

        // Facebook comet renders one line per paragraph — every non-empty
        // line is a paragraph, the first one is the title.
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\n+/', $text))));

        $title = $this->cleanTitle($paragraphs[0]);
        array_shift($paragraphs);

        // Drop boilerplate paragraphs: hashtags/mentions and bare social links.
        $paragraphs = array_values(array_filter($paragraphs, function ($p) {
            $tokens = preg_split('/\s+/', $p);
            $noise = count(array_filter($tokens, fn ($t) =>
                Str::startsWith($t, ['#', '@', 'http://', 'https://', 'www.'])));
            return $noise < count($tokens);
        }));

        if (empty($paragraphs)) {
            $paragraphs = [$text]; // degenerate caption: publish as-is
        }

        $body = collect($paragraphs)
            ->map(fn ($p) => '<p>' . e($p) . '</p>')
            ->implode("\n\n");

        $summary = Str::limit(trim(preg_replace('/\s+/', ' ', $paragraphs[0])), 480);

        $date = substr($post['published_at'], 0, 10);
        $slug = Str::slug(Str::words($title, 12, '')) . '-' . $date;

        return [
            'title' => $title,
            'summary' => $summary,
            'content_body' => $body,
            'published_at' => $post['published_at'],
            'slug' => $slug,
        ];
    }

    private function cleanTitle(string $line): string
    {
        // strip leading/trailing emoji, pipes, hashtags
        $line = trim(preg_replace('/[#*_]|[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{FE0F}]/u', '', $line));
        $line = trim($line, " \t\-–—|:");

        // SHOUTING captions → Title Case
        $letters = preg_replace('/[^A-Za-z]/', '', $line);
        if ($letters !== '' && strlen(preg_replace('/[^A-Z]/', '', $letters)) / strlen($letters) > 0.7) {
            $line = Str::title(mb_strtolower($line));
        }

        return Str::limit($line, 180, '');
    }

    private function downloadImages(string $base, string $fbId, array $images, string $slug): int
    {
        $dir = public_path('media/news/news');
        File::ensureDirectoryExists($dir);

        $saved = 0;
        foreach ($images as $filename) {
            $resp = $this->http()->get("{$base}/facebook/media/{$fbId}/{$filename}");
            if (! $resp->successful() || strlen($resp->body()) < 1000) {
                Log::warning("facebook-sync: image download failed", ['post' => $fbId, 'file' => $filename]);
                continue;
            }
            $saved++;
            File::put("{$dir}/{$slug}-{$saved}.jpeg", $resp->body());
        }

        return $saved;
    }

    private function galleryHtml(string $slug, int $count): string
    {
        $imgs = collect(range(1, $count))->map(fn ($i) =>
            "<img src=\"/media/news/news/{$slug}-{$i}.jpeg\" alt=\"\" class=\"rounded-xl shadow-md w-full h-48 object-cover\">"
        )->implode("\n");

        return "<div class=\"news-gallery grid grid-cols-2 md:grid-cols-3 gap-3 mt-8\">\n{$imgs}\n</div>";
    }

    private function ack(string $base, string $fbId, ?string $newsItemId): void
    {
        if ($this->option('dry-run')) {
            return;
        }
        $resp = $this->http()->post("{$base}/facebook/ack", [
            'post_id' => $fbId,
            'news_item_id' => $newsItemId,
        ]);
        if (! $resp->successful()) {
            Log::warning('facebook-sync: ack failed', ['post' => $fbId, 'status' => $resp->status()]);
        }
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken((string) config('services.translator.token'))
            ->timeout(120)
            ->connectTimeout(10);
    }
}
