<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\VideoGallery;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'videoCount'             => VideoGallery::count(),
            'publishedVideoCount'    => VideoGallery::published()->count(),
            'newsletterCount'        => Newsletter::count(),
            'publishedNewsletterCount' => Newsletter::published()->count(),
        ]);
    }
}
