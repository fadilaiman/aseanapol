<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalLibraryItem;
use App\Models\Event;
use App\Models\Newsletter;
use App\Models\NewsItem;
use App\Models\Publication;
use App\Models\VideoGallery;

class DashboardController extends Controller
{
    public function index()
    {
        $latestNews = NewsItem::orderBy('published_at', 'desc')->first(['title', 'published_at']);

        return view('admin.dashboard', [
            'newsCount'              => NewsItem::count(),
            'latestNews'             => $latestNews,
            'eventCount'             => Event::count(),
            'publishedEventCount'    => Event::published()->count(),
            'publicationCount'       => Publication::count(),
            'publishedPublicationCount' => Publication::published()->count(),
            'libraryCount'           => DigitalLibraryItem::count(),
            'publishedLibraryCount'  => DigitalLibraryItem::where('is_published', true)->count(),
            'videoCount'             => VideoGallery::count(),
            'publishedVideoCount'    => VideoGallery::published()->count(),
            'newsletterCount'        => Newsletter::count(),
            'publishedNewsletterCount' => Newsletter::published()->count(),
            'cmsPageCount'           => collect(\App\Support\Cms::allGroups())->sum(fn ($g) => count($g['pages'])),
            'cmsCustomisedPages'     => \App\Models\CmsBlock::distinct()->count('page_key'),
        ]);
    }
}
