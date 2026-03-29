<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;

class NewsController extends Controller
{
    public function index()
    {
        $articles = NewsItem::orderBy('published_at', 'desc')
            ->paginate(12);

        return view('news.index', compact('articles'));
    }

    public function show(string $locale, string $slug)
    {
        $decoded = rawurldecode($slug);

        // Try exact slug first; fall back to ASCII-transliterated version so
        // articles whose original Sitefinity slug contained accented characters
        // (é, ó, ş, ᵗʰ …) are still found after the slugs are normalised.
        $article = NewsItem::where('slug', $decoded)->first()
            ?? NewsItem::where('slug', $this->asciiSlug($decoded))->firstOrFail();

        return view('news.show', compact('article'));
    }

    private function asciiSlug(string $slug): string
    {
        if (function_exists('transliterator_transliterate')) {
            $slug = (string) transliterator_transliterate('Any-Latin; Latin-ASCII', $slug);
        } else {
            $slug = (string) (iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug) ?: $slug);
        }
        return preg_replace('/[^\x20-\x7E]/', '', $slug);
    }
}
