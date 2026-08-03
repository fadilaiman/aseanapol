<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Safety net for news inserted by raw-SQL deploy scripts: hash-diff and
// queue any missing/stale translations.
Schedule::command('translate:sync-news')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('queue:prune-failed', ['--hours' => 48])->daily();

// Facebook auto-ingest: the scraper on the GPU box runs ~08:20/14:20 MYT;
// we pull ~40 min later (times below are UTC = MYT-8).
Schedule::command('news:sync-facebook')->twiceDailyAt(1, 7, 0)->withoutOverlapping();

// Photo Gallery: rebuilds gallery_images from news_items + public/media so
// new news photos (deploy scripts, Facebook auto-ingest) and any manually
// uploaded governance/partner/observer photos appear without a manual step.
Schedule::command('gallery:reindex')->everyFifteenMinutes()->withoutOverlapping();
