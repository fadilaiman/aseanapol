<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsItemController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $query = NewsItem::orderBy('published_at', 'desc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%");
            });
        }

        $articles = $query->paginate(20)->withQueryString();
        return view('admin.news.index', compact('articles', 'q'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:500',
            'summary'        => 'nullable|string',
            'content'        => 'nullable|string',
            'author'         => 'nullable|string|max:255',
            'published_at'   => 'nullable|date',
            'thumbnail_url'  => 'nullable|url|max:1000',
            'thumbnail_file' => 'nullable|file|image|max:5120',
            'slug'           => 'nullable|string|max:500',
        ]);

        $data['id']                 = (string) Str::uuid();
        $data['thumbnail']          = $this->resolveThumbnail($request);
        $data['slug']               = Str::slug($data['slug'] ?: $data['title']);
        $data['is_upcoming_event']  = $request->boolean('is_upcoming_event');

        $base = $data['slug'];
        $i = 1;
        while (NewsItem::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        NewsItem::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Article created.');
    }

    public function edit(NewsItem $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, NewsItem $news)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:500',
            'summary'        => 'nullable|string',
            'content'        => 'nullable|string',
            'author'         => 'nullable|string|max:255',
            'published_at'   => 'nullable|date',
            'thumbnail_url'  => 'nullable|url|max:1000',
            'thumbnail_file' => 'nullable|file|image|max:5120',
            'slug'           => 'required|string|max:500',
        ]);

        $resolved = $this->resolveThumbnail($request, $news->thumbnail);
        if ($resolved !== null) {
            $data['thumbnail'] = $resolved;
        }

        $slug = Str::slug($data['slug']);
        $base = $slug;
        $i = 1;
        while (NewsItem::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug']              = $slug;
        $data['is_upcoming_event'] = $request->boolean('is_upcoming_event');

        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Article updated.');
    }

    private function resolveThumbnail(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            $path = $request->file('thumbnail_file')->store('news/thumbnails', 'public');
            return Storage::url($path);
        }

        $url = trim($request->input('thumbnail_url', ''));
        if ($url !== '') {
            return $url;
        }

        return $current;
    }

    public function destroy(NewsItem $news)
    {
        $news->delete();
        return back()->with('success', 'Article deleted.');
    }
}
