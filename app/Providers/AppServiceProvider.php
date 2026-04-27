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
                $row = DB::table('visitor_stats')
                    ->selectRaw('SUM(page_views) as total_views, SUM(unique_visitors) as total_unique')
                    ->first();
                $view->with('visitorTotalViews', (int) ($row->total_views ?? 0));
                $view->with('visitorTotalUnique', (int) ($row->total_unique ?? 0));
            } catch (\Throwable) {
                $view->with('visitorTotalViews', 0);
                $view->with('visitorTotalUnique', 0);
            }
        });
    }
}
