<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewsItemController extends Controller
{
    public function index(Request $request)
    {
        $q = is_string($request->query('q')) ? trim($request->query('q')) : '';
        $query = NewsItem::orderBy('published_at', 'desc');

        if ($q !== '') {
            $like = '%' . addcslashes(mb_substr($q, 0, 200), '%_\\') . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('title', 'like', $like)
                    ->orWhere('author', 'like', $like);
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
            'sponsors_label' => 'nullable|string|max:160',
            'sponsors'                => 'nullable|array|max:8',
            'sponsors.*.name'         => 'nullable|string|max:120',
            'sponsors.*.url'          => ['nullable', 'string', 'max:255', 'regex:#^https?://[^\s<>"]+$#i'],
            'sponsors.*.logo_url'     => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/(?!/)|media/|storage/)(?!.*\.\.)[^\s<>"]+$#i'],
            'sponsors.*.logo_existing' => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/(?!/)|media/|storage/)(?!.*\.\.)[^\s<>"]+$#i'],
            'sponsors.*.logo_file'    => 'nullable|file|image|mimes:png,jpg,jpeg,webp,gif|max:2048',
        ]);

        $data['id']                 = (string) Str::uuid();
        $data['thumbnail']          = $this->resolveThumbnail($request);
        $data['slug']               = Str::limit(Str::slug((($data['slug'] ?? '') ?: $data['title'])), 240, '') ?: 'article';
        $data['is_upcoming_event']  = $request->boolean('is_upcoming_event');
        $data['content']            = $this->cleanContent($data['content'] ?? null);
        $data['published_at']       = $data['published_at'] ?? now();

        $base = $data['slug'];
        $i = 1;
        while (NewsItem::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        $data['sponsors'] = $this->buildSponsors($request, null)[0];
        unset($data['sponsors_label']);

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
            'sponsors_label' => 'nullable|string|max:160',
            'sponsors'                => 'nullable|array|max:8',
            'sponsors.*.name'         => 'nullable|string|max:120',
            'sponsors.*.url'          => ['nullable', 'string', 'max:255', 'regex:#^https?://[^\s<>"]+$#i'],
            'sponsors.*.logo_url'     => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/(?!/)|media/|storage/)(?!.*\.\.)[^\s<>"]+$#i'],
            'sponsors.*.logo_existing' => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/(?!/)|media/|storage/)(?!.*\.\.)[^\s<>"]+$#i'],
            'sponsors.*.logo_file'    => 'nullable|file|image|mimes:png,jpg,jpeg,webp,gif|max:2048',
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

        [$data['sponsors'], $stale] = $this->buildSponsors($request, $news->sponsors);
        unset($data['sponsors_label']);

        $news->update($data);
        foreach ($stale as $path) {
            $this->deleteSponsorLogo($path);
        }
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

        $url = trim((string) $request->input('thumbnail_url', ''));
        if ($url !== '') {
            // Never let a typed URL claim a file this module uploaded (destroy() deletes /storage/news/thumbnails/*),
            // nor smuggle path traversal: only the record's own current value may keep pointing there.
            if ($url !== $current && (preg_match('#(\\.\\.|%2e|%5c|\\\\)#i', $url) || preg_match('#/?storage/news/(thumbnails|sponsors)/#i', $url))) {
                throw ValidationException::withMessages(['thumbnail_url' => 'This thumbnail path is not allowed. Upload an image instead.']);
            }

            return $url;
        }

        return $current;
    }

    public function destroy(NewsItem $news)
    {
        // remove a thumbnail we uploaded ourselves (never touch media/ or external URLs)
        if ($news->thumbnail && preg_match('#^/storage/(news/thumbnails/[A-Za-z0-9._-]+)$#', $news->thumbnail, $m)
            && ! NewsItem::where('id', '!=', $news->id)->where('thumbnail', $news->thumbnail)->exists()) {
            Storage::disk('public')->delete($m[1]);
        }
        foreach ($news->sponsorItems() as $item) {
            if (! $this->logoUsedElsewhere($item['logo'], $news->id)) {
                $this->deleteSponsorLogo($item['logo']);
            }
        }
        $news->delete();
        return back()->with('success', 'Article deleted.');
    }

    private const SPONSOR_DIR = 'news/sponsors';

    /**
     * Turn the posted repeater into the stored {label, items[]} structure.
     * Returns [structure|null, logo paths that are no longer referenced].
     */
    private function buildSponsors(Request $request, ?array $current): array
    {
        $old = [];
        foreach ((array) ($current['items'] ?? []) as $it) {
            if (is_array($it) && ! empty($it['logo'])) {
                $old[] = $it['logo'];
            }
        }

        $rows = [];
        $errors = [];
        $n = 0;
        foreach ((array) $request->input('sponsors', []) as $key => $row) {
            $n++;
            $file = $request->file("sponsors.$key.logo_file");
            $name = trim((string) ($row['name'] ?? ''));
            $url  = trim((string) ($row['url'] ?? ''));
            $logoUrl = trim((string) ($row['logo_url'] ?? ''));
            $existing = trim((string) ($row['logo_existing'] ?? ''));
            if ($name === '' && $url === '' && $logoUrl === '' && $existing === '' && ! $file) {
                continue; // blank row
            }
            if ($name === '') {
                $errors["sponsors.$key.name"] = "Sponsor row $n needs a name (it is also the logo's alt text).";
            }
            if (! $file && $logoUrl === '' && $existing === '') {
                $errors["sponsors.$key.logo_file"] = "Sponsor row $n needs a logo (upload a file or give a media path/URL).";
            }
            foreach (['logoUrl' => 'logo_file', 'existing' => 'logo_file'] as $var => $errKey) {
                $v = $$var;
                if ($v !== '' && preg_match('#/?storage/' . preg_quote(self::SPONSOR_DIR, '#') . '/#i', $v) && ! in_array($v, $old, true)) {
                    $errors["sponsors.$key.$errKey"] = "Sponsor row $n: that logo path belongs to another article. Upload the logo instead.";
                }
            }
            $rows[] = ['name' => $name, 'url' => $url ?: null, 'file' => $file, 'logo_url' => $logoUrl, 'existing' => $existing];
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        $items = [];
        foreach ($rows as $r) {
            if ($r['file'] && $r['file']->isValid()) {
                $ext = $r['file']->extension() ?: 'png'; // from the real content, never the client's filename
                $fname = 'sponsor-' . now()->format('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $logo = 'storage/' . $r['file']->storeAs(self::SPONSOR_DIR, $fname, 'public');
            } elseif ($r['logo_url'] !== '') {
                $logo = $r['logo_url'];
            } else {
                $logo = $r['existing'];
            }
            $items[] = ['name' => $r['name'], 'logo' => $logo, 'url' => $r['url']];
        }

        $label = trim((string) $request->input('sponsors_label', ''));
        if (! $items && $label === '') {
            return [null, $old];
        }

        $kept = array_column($items, 'logo');
        $stale = array_values(array_diff($old, $kept));

        return [['label' => $label !== '' ? $label : null, 'items' => $items], $stale];
    }

    private function logoUsedElsewhere(?string $path, string $exceptId): bool
    {
        return $path && NewsItem::where('id', '!=', $exceptId)->where('sponsors', 'like', '%' . str_replace(['%', '_'], ['\%', '\_'], basename($path)) . '%')->exists();
    }

    /** Only ever deletes files this feature uploaded (public disk, news/sponsors/). */
    private function deleteSponsorLogo(?string $path): void
    {
        if ($path && preg_match('#^/?storage/(' . preg_quote(self::SPONSOR_DIR, '#') . '/[A-Za-z0-9._-]+)$#', $path, $m)) {
            Storage::disk('public')->delete($m[1]);
        }
    }
}
