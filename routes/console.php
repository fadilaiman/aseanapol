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
