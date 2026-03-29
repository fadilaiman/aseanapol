<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index(string $locale = 'en')
    {
        $latestNews = DB::table('news_items')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get(['id', 'title', 'slug', 'thumbnail', 'summary', 'published_at']);

        return view('landing', compact('latestNews'));
    }
}
