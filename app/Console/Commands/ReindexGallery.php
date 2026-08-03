<?php

namespace App\Console\Commands;

use App\Models\NewsItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Rebuilds the gallery_images index (used by the Photo Gallery page) from
 * two sources:
 *  - news_items: thumbnail + every <img> inside content, dated by
 *    published_at. This is what makes new deploy_news_*.py runs and the
 *    Facebook auto-ingest cron show up in the gallery without any manual
 *    step, and gives news photos a real "event date" instead of upload order.
 *  - filesystem-only albums (governance/partners/observers/activities/events)
 *    that have no DB date metadata — dated by file mtime as the closest
 *    available proxy.
 *
 * Any file under the news directories not referenced by a news_items row
 * (e.g. a stray upload) is still included, dated by mtime, so nothing
 * silently disappears from the gallery.
 *
 * Cheap enough (~7k rows) to fully rebuild each run rather than diff.
 */
class ReindexGallery extends Command
{
    protected $signature = 'gallery:reindex';

    protected $description = 'Rebuild the gallery_images index from news_items and public/media';

    private const FS_ALBUMS = [
        'activities' => ['rotating-image/rotating-image', 'default-album/default-album'],
        'events'     => ['events/events'],
        'governance' => ['governance/governance'],
        'partners'   => ['dialogue-partner/dialogue-partner'],
        'observers'  => ['observer/observer'],
    ];

    private const NEWS_DIRS = ['news/news', 'news/facebook', 'news/scrape'];

    private const IMAGE_EXT_RE = '/\.(jpe?g|png|gif|webp)$/i';

    public function handle(): int
    {
        $claimed = []; // normalized path => true
        $rows = [];

        foreach (NewsItem::select('thumbnail', 'content', 'published_at', 'created_at')->cursor() as $item) {
            $date = ($item->published_at ?? $item->created_at)?->toDateString();
            if (! $date) {
                continue;
            }

            $paths = [];
            if ($item->thumbnail) {
                $paths[] = $item->thumbnail;
            }
            if ($item->content && preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $item->content, $m)) {
                $paths = array_merge($paths, $m[1]);
            }

            foreach ($paths as $raw) {
                $path = $this->normalizePath($raw);
                if (! $path || isset($claimed[$path])) {
                    continue;
                }
                $claimed[$path] = true;
                $rows[] = ['album_key' => 'news', 'path' => $path, 'event_date' => $date];
            }
        }

        foreach (self::NEWS_DIRS as $dir) {
            foreach ($this->globImages($dir) as $abs => $rel) {
                if (isset($claimed[$rel])) {
                    continue;
                }
                $claimed[$rel] = true;
                $rows[] = ['album_key' => 'news', 'path' => $rel, 'event_date' => date('Y-m-d', filemtime($abs))];
            }
        }

        foreach (self::FS_ALBUMS as $key => $dirs) {
            foreach ($dirs as $dir) {
                foreach ($this->globImages($dir) as $abs => $rel) {
                    if (isset($claimed[$rel])) {
                        continue;
                    }
                    $claimed[$rel] = true;
                    $rows[] = ['album_key' => $key, 'path' => $rel, 'event_date' => date('Y-m-d', filemtime($abs))];
                }
            }
        }

        // insertOrIgnore: MySQL's default collation compares `path` case-insensitively,
        // so filesystem-case variants of what is otherwise a duplicate path would
        // otherwise trip the unique constraint mid-batch.
        // TRUNCATE auto-commits in MySQL, so this isn't wrapped in a transaction.
        $now = now();
        DB::table('gallery_images')->truncate();
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('gallery_images')->insertOrIgnore(array_map(
                fn ($r) => $r + ['created_at' => $now, 'updated_at' => $now],
                $chunk
            ));
        }

        $this->info(count($rows) . ' image(s) indexed.');

        return self::SUCCESS;
    }

    private function normalizePath(string $src): ?string
    {
        $src = trim($src);
        $src = preg_replace('#^https?://[^/]+/#i', '', $src);
        $src = ltrim($src, '/');
        if (! str_starts_with($src, 'media/') || ! preg_match(self::IMAGE_EXT_RE, $src)) {
            return null;
        }

        return $src;
    }

    /** @return array<string,string> absolute path => path relative to public/ */
    private function globImages(string $relDir): array
    {
        $base = public_path('media/' . $relDir);
        $ext = '{jpg,jpeg,png,gif,webp,JPG,JPEG,PNG,WEBP}';

        $found = [];
        foreach (array_merge(
            glob("{$base}/*.{$ext}", GLOB_BRACE) ?: [],
            glob("{$base}/*/*.{$ext}", GLOB_BRACE) ?: []
        ) as $abs) {
            $rel = str_replace('\\', '/', ltrim(str_replace($base, '', $abs), '/\\'));
            $found[$abs] = 'media/' . $relDir . '/' . $rel;
        }

        return $found;
    }
}
