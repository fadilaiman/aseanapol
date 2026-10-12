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
        $q = is_string($request->query('q')) ? trim($request->query('q')) : '';
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
            'title'          => 'required|string|max:255',
            'summary'        => 'nullable|string',
            'content'        => 'nullable|string',
            'author'         => 'nullable|string|max:255',
            'published_at'   => 'nullable|date',
            'thumbnail_url'  => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'thumbnail_file' => 'nullable|file|image|max:5120',
            'slug'           => 'nullable|string|max:255',
        ]);

        $data['id']                 = (string) Str::uuid();
        $data['thumbnail']          = $this->resolveThumbnail($request);
        $data['slug']               = Str::limit(Str::slug($data['slug'] ?: $data['title']), 240, '') ?: 'article';
        $data['is_upcoming_event']  = $request->boolean('is_upcoming_event');
        $data['content']            = $this->cleanContent($data['content'] ?? null);
        $data['published_at']       = $data['published_at'] ?? now();

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
            'title'          => 'required|string|max:255',
            'summary'        => 'nullable|string',
            'content'        => 'nullable|string',
            'author'         => 'nullable|string|max:255',
            'published_at'   => 'nullable|date',
            'thumbnail_url'  => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'thumbnail_file' => 'nullable|file|image|max:5120',
            'slug'           => 'required|string|max:255',
        ]);

        $resolved = $this->resolveThumbnail($request, $news->thumbnail);
        if ($resolved !== null) {
            $data['thumbnail'] = $resolved;
        }

        // Keep an existing slug byte-for-byte (older slugs contain dots/brackets that
        // Str::slug() would rewrite, breaking links such as press-release entries).
        $slug = $data['slug'] === $news->slug ? $news->slug : (Str::limit(Str::slug($data['slug']), 240, '') ?: $news->slug);
        $base = $slug;
        $i = 1;
        while (NewsItem::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug']              = $slug;
        $data['is_upcoming_event'] = $request->boolean('is_upcoming_event');
        $data['content']           = $this->cleanContent($data['content'] ?? null);

        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Article updated.');
    }

    /**
     * Article HTML is rendered unescaped on the public site. Keep the markup
     * (classes/inline styles are used by the photo grids) but drop anything executable.
     */
    private function cleanContent(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }
        $html = \App\Support\HtmlSanitizer::cleanRich($html);

        return $html;
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
        // remove a thumbnail we uploaded ourselves (never touch media/ or external URLs)
        if ($news->thumbnail && str_starts_with($news->thumbnail, '/storage/news/thumbnails/')) {
            Storage::disk('public')->delete(substr($news->thumbnail, strlen('/storage/')));
        }
        $news->delete();
        return back()->with('success', 'Article deleted.');
    }
}
