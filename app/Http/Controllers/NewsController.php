<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Models\NewsTranslation;

class NewsController extends Controller
{
    public function index()
    {
        $q = is_string(request()->query('q')) ? trim(request()->query('q')) : '';

        $query = NewsItem::orderBy('published_at', 'desc');

        if ($q !== '') {
            // literal search: % _ \ typed by the visitor must not act as LIKE wildcards (cheap-DoS / data-probing)
            $like = '%' . addcslashes(mb_substr($q, 0, 200), '%_\\') . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('title',   'like', $like)
                    ->orWhere('summary', 'like', $like)
                    ->orWhere('content', 'like', $like);
            });
        }

        $articles = $query->paginate(12)->withQueryString();

        $locale = app()->getLocale();
        if ($locale !== 'en') {
            $translations = NewsTranslation::forItems($articles->getCollection(), $locale);
            $articles->getCollection()->transform(function (NewsItem $item) use ($translations) {
                $item->applyTranslationRow($translations->get($item->id));
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

        $article->applyTranslation($locale);

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
