<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;

/** Public renderer for pages made in Admin > Custom Pages. Unpublished or unknown slug = 404. */
class CustomPageController extends Controller
{
    public function __invoke(string $locale, string $slug)
    {
        $page = CustomPage::published()->where('slug', $slug)->firstOrFail();

        return view('custom-page', compact('page'));
    }
}
