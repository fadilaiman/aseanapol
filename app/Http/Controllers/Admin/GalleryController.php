<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Models\GalleryImage;
use App\Models\GalleryOverride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Admin for the auto-indexed Photo Gallery.
 *
 * gallery_images is rebuilt from news/albums every 15 minutes (gallery:reindex), so admin decisions are kept in
 * gallery_overrides and re-applied by the reindex: hidden photos stay hidden, uploaded photos (public/media/gallery/)
 * stay in their album. "Delete" on a photo that comes from a news article / album folder can only HIDE it from the
 * gallery (the file and the article keep it); uploaded photos are deleted for real.
 */
class GalleryController extends Controller
{
    private const MANUAL_DIR = 'media/gallery';

    public function index(Request $request)
    {
        $str = fn (string $k): string => is_string($v = $request->query($k)) ? trim($v) : '';
        $q = $str('q');
        $album = $str('album');
        $status = $str('status');   // '' = visible, hidden, all
        $source = $str('source');   // '' | uploaded | indexed

        $visible = DB::table('gallery_images')->selectRaw("path, album_key, event_date, caption, 'visible' as status");
        $hidden = DB::table('gallery_overrides')->where('is_hidden', true)->selectRaw("path, album_key, event_date, caption, 'hidden' as status");
        $union = $status === 'hidden' ? $hidden : ($status === 'all' ? $visible->unionAll($hidden) : $visible);

        $query = DB::query()->fromSub($union, 'g');
        if ($q !== '') {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
            $query->where(fn ($w) => $w->where('path', 'like', $like)->orWhere('caption', 'like', $like));
        }
        if (isset(PageController::GALLERY_ALBUM_LABELS[$album])) {
            $query->where('album_key', $album);
        }
        if ($source === 'uploaded') {
            $query->where('path', 'like', self::MANUAL_DIR . '/%');
        } elseif ($source === 'indexed') {
            $query->where('path', 'not like', self::MANUAL_DIR . '/%');
        }

        $photos = $query->orderByDesc('event_date')->orderBy('path')->paginate(48)->withQueryString();

        return view('admin.gallery.index', [
            'photos'  => $photos,
            'albums'  => PageController::GALLERY_ALBUM_LABELS,
            'filters' => compact('q', 'album', 'status', 'source'),
            'hiddenCount' => GalleryOverride::where('is_hidden', true)->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos'     => 'required|array|max:30',
            'photos.*'   => 'file|image|mimes:jpg,jpeg,png,webp,gif|max:8192',
            'album_key'  => 'required|in:' . implode(',', array_keys(PageController::GALLERY_ALBUM_LABELS)),
            'event_date' => 'required|date|before_or_equal:today',
            'caption'    => 'nullable|string|max:255',
        ], [
            'photos.required'  => 'Choose at least one photo.',
            'photos.*.max'     => 'Each photo may be at most 8 MB.',
            'photos.*.image'   => 'Only JPG, PNG, WebP or GIF images are allowed.',
            'photos.*.mimes'   => 'Only JPG, PNG, WebP or GIF images are allowed.',
            'photos.*.uploaded' => 'A photo could not be uploaded: it is larger than the server upload limit (' . ini_get('upload_max_filesize') . ' per file).',
        ]);

        $date = \Carbon\Carbon::parse($request->input('event_date'))->toDateString();
        $caption = trim((string) $request->input('caption')) ?: null;
        $n = 0;
        foreach ((array) $request->file('photos') as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }
            // unique filename every time (Cloudflare caches media URLs as immutable)
            $name = (Str::limit(Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 50, '') ?: 'photo')
                . '-' . now()->format('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . ($file->extension() ?: 'jpg');
            $file->move(public_path(self::MANUAL_DIR), $name);
            $path = self::MANUAL_DIR . '/' . $name;

            GalleryOverride::create(['path' => $path, 'album_key' => $request->input('album_key'), 'event_date' => $date, 'caption' => $caption, 'is_manual' => true, 'is_hidden' => false]);
            GalleryImage::insertOrIgnore([['album_key' => $request->input('album_key'), 'path' => $path, 'event_date' => $date, 'caption' => $caption, 'created_at' => now(), 'updated_at' => now()]]);
            $n++;
        }

        return redirect()->route('admin.gallery.index')->with('success', $n . ' photo(s) added to the gallery.');
    }

    /** Hide / unhide one photo (works for indexed and uploaded photos). */
    public function toggle(Request $request)
    {
        $path = $this->path($request);
        $override = GalleryOverride::where('path', $path)->first();

        if ($override && $override->is_hidden) { // unhide
            if ($override->is_manual) {
                $override->update(['is_hidden' => false]);
            } else {
                $override->delete();
            }
            if ($override->event_date) {
                GalleryImage::insertOrIgnore([['album_key' => $override->album_key, 'path' => $path, 'event_date' => $override->event_date->toDateString(), 'caption' => $override->caption, 'created_at' => now(), 'updated_at' => now()]]);
            }

            return back()->with('success', 'Photo is visible in the gallery again.');
        }

        $this->hide($path);

        return back()->with('success', 'Photo hidden from the gallery. It stays in its news article; use the "Hidden" filter to bring it back.');
    }

    public function destroy(Request $request)
    {
        $path = $this->path($request);
        $override = GalleryOverride::where('path', $path)->first();

        if ($override && $override->is_manual) {
            GalleryImage::where('path', $path)->delete();
            $override->delete();
            $abs = public_path($path);
            if (str_starts_with($path, self::MANUAL_DIR . '/') && is_file($abs)) {
                @unlink($abs);
            }

            return back()->with('success', 'Uploaded photo deleted.');
        }

        // Photos that come from news articles / album folders cannot be deleted from here: hide them permanently.
        $this->hide($path);

        return back()->with('success', 'Photo removed from the gallery (hidden permanently; the file and any news article using it are untouched). Use the "Hidden" filter to restore it.');
    }

    private function hide(string $path): void
    {
        $row = GalleryImage::where('path', $path)->first();
        $override = GalleryOverride::where('path', $path)->first();
        if ($override) {
            $override->update(['is_hidden' => true]);
        } else {
            GalleryOverride::create([
                'path' => $path, 'album_key' => $row->album_key ?? 'news', 'event_date' => $row?->event_date, 'caption' => $row->caption ?? null,
                'is_manual' => false, 'is_hidden' => true,
            ]);
        }
        GalleryImage::where('path', $path)->delete();
    }

    private function path(Request $request): string
    {
        $path = (string) $request->input('path');
        $known = GalleryImage::where('path', $path)->exists() || GalleryOverride::where('path', $path)->exists();
        abort_unless($known && str_starts_with($path, 'media/') && ! str_contains($path, '..'), 404);

        return $path;
    }
}
