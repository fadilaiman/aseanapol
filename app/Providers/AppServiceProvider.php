<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('layouts.app', function ($view) {
            try {
                $today = now()->toDateString();
                $total = DB::table('visitor_stats')
                    ->selectRaw('SUM(page_views) as total_views, SUM(unique_visitors) as total_unique')
                    ->first();
                $daily = DB::table('visitor_stats')
                    ->where('date', $today)
                    ->selectRaw('page_views, unique_visitors')
                    ->first();
                $history = DB::table('visitor_stats')
                    ->where('date', '>=', now()->subDays(29)->toDateString())
                    ->orderBy('date')
                    ->get(['date', 'unique_visitors']);
                $view->with('visitorTotalViews', (int) ($total->total_views ?? 0));
                $view->with('visitorTotalUnique', (int) ($total->total_unique ?? 0));
                $view->with('visitorDailyViews', (int) ($daily->page_views ?? 0));
                $view->with('visitorDailyUnique', (int) ($daily->unique_visitors ?? 0));
                $view->with('visitorHistory', $history);
            } catch (\Throwable) {
                $view->with('visitorTotalViews', 0);
                $view->with('visitorTotalUnique', 0);
                $view->with('visitorDailyViews', 0);
                $view->with('visitorDailyUnique', 0);
                $view->with('visitorHistory', collect());
            }
        });
    }
}
