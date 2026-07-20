<?php

namespace App\Console\Commands;

use App\Jobs\TranslateNewsItemJob;
use App\Models\NewsItem;
use App\Models\NewsTranslation;
use Illuminate\Console\Command;

/**
 * Hash-diff based sync: compares each news item's current content hash with
 * every locale's stored translation and (re)queues whatever is missing or
 * stale. Deliberately NOT model-event based — news rows are often inserted
 * by raw-SQL deploy scripts.
 */
class SyncNewsTranslations extends Command
{
    protected $signature = 'translate:sync-news
        {--sync : Run translations in the foreground instead of queueing}
        {--locale=* : Only these locales}
        {--id=* : Only these news item ids}';

    protected $description = 'Queue translation jobs for news items whose translations are missing or stale';

    public function handle(): int
    {
        if (! config('services.translator.url')) {
            $this->warn('services.translator.url not configured — nothing to do.');
            return self::SUCCESS;
        }

        $locales = $this->option('locale') ?: config('services.translator.locales');

        $items = NewsItem::query()
            ->when($this->option('id'), fn ($q, $ids) => $q->whereIn('id', $ids))
            ->get(['id', 'title', 'summary', 'content']);

        $existing = NewsTranslation::whereIn('news_item_id', $items->pluck('id'))
            ->get()
            ->keyBy(fn ($t) => "{$t->news_item_id}:{$t->locale}");

        $dispatched = 0;

        foreach ($items as $item) {
            $hash = $item->contentHash();

            foreach ($locales as $locale) {
                $row = $existing->get("{$item->id}:{$locale}");

                if ($row && $row->source_hash === $hash) {
                    if ($row->status === 'completed') {
                        continue; // up to date
                    }
                    // pending: assume a job is in flight unless it looks stuck
                    if ($row->status === 'pending' && $row->updated_at->gt(now()->subMinutes(30))) {
                        continue;
                    }
                    // failed: retry hourly so a gateway outage self-heals
                    if ($row->status === 'failed' && $row->updated_at->gt(now()->subHour())) {
                        continue;
                    }
                }

                NewsTranslation::updateOrCreate(
                    ['news_item_id' => $item->id, 'locale' => $locale],
                    ['source_hash' => $hash, 'status' => 'pending']
                );

                $job = new TranslateNewsItemJob($item->id, $locale, $hash);
                $this->option('sync') ? dispatch_sync($job) : dispatch($job);
                $dispatched++;
            }
        }

        $this->info("Dispatched {$dispatched} translation job(s) for {$items->count()} news item(s).");

        return self::SUCCESS;
    }
}
