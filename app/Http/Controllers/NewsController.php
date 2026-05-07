<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Services\TranslationService;

class NewsController extends Controller
{
    public function index()
    {
        $q = trim(request()->query('q', ''));

        $query = NewsItem::orderBy('published_at', 'desc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title',   'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $articles = $query->paginate(12)->withQueryString();

        $locale = app()->getLocale();
        if ($locale !== 'en') {
            $translator = app(TranslationService::class);
            $articles->getCollection()->transform(function (NewsItem $item) use ($translator, $locale) {
                $item->title   = $translator->translate($item->title, $locale);
                $item->summary = $item->summary ? $translator->translate($item->summary, $locale) : null;
                return $item;
            });
        }

        return view('news.index', compact('articles', 'q'));
    }

    public function show(string $locale, string $slug)
    {
        $decoded = rawurldecode($slug);

        $article = NewsItem::where('slug', $decoded)->first()
            ?? NewsItem::where('slug', $this->asciiSlug($decoded))->firstOrFail();

        if ($locale !== 'en') {
            $translator = app(TranslationService::class);
            $article->title   = $translator->translate($article->title, $locale);
            $article->content = $article->content
                ? $translator->translateHtml($article->content, $locale)
                : null;
        }

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
