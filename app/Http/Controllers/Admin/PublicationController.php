<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicationController extends Controller
{
    private const DIR = 'publications';

    public function index()
    {
        $publications = Publication::ordered()->get();

        return view('admin.publications.index', compact('publications'));
    }

    public function create()
    {
        $first = (int) Publication::min('sort_order');

        return view('admin.publications.create', ['publication' => new Publication([
            'kind' => 'bulletin', 'is_published' => true, 'sort_order' => max(0, $first - 5),
            'color' => 'from-primary to-primary/70',
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->applyFiles($request, $data, null);

        Publication::create($data);

        return redirect()->route('admin.publications.index')->with('success', 'Publication added.');
    }

    public function edit(Publication $publication)
    {
        return view('admin.publications.edit', compact('publication'));
    }

    public function update(Request $request, Publication $publication)
    {
        $data = $this->validated($request, $publication);
        $data = $this->applyFiles($request, $data, $publication);

        $publication->update($data);

        return redirect()->route('admin.publications.index')->with('success', 'Publication updated.');
    }

    public function destroy(Publication $publication)
    {
        $this->deleteUploaded($publication->pdf_path);
        $this->deleteUploaded($publication->cover_path);
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('success', 'Publication removed.');
    }

    public function toggle(Publication $publication)
    {
        $publication->update(['is_published' => !$publication->is_published]);

        return back()->with('success', 'Publication ' . ($publication->is_published ? 'published' : 'hidden') . '.');
    }

    private function validated(Request $request, ?Publication $publication = null): array
    {
        // Slug falls back to the title when left empty.
        if (!trim((string) $request->input('slug'))) {
            $request->merge(['slug' => Str::slug((string) $request->input('title'))]);
        } else {
            $request->merge(['slug' => Str::slug((string) $request->input('slug'))]);
        }

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'edition'    => 'required|string|max:50',
            'kind'       => 'required|in:bulletin,magazine',
            'slug'       => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                             Rule::unique('publications', 'slug')->ignore($publication?->id)],
            'theme'      => 'nullable|string|max:255',
            'year'       => 'nullable|string|max:10',
            'size'       => 'nullable|string|max:50',
            'summary'    => 'nullable|string|max:5000',
            'color'      => ['required', Rule::in(array_keys(Publication::COLORS))],
            'pdf'        => 'nullable|file|mimes:pdf|max:512000',
            'cover'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ], [
            'slug.regex' => 'The URL slug may only contain lowercase letters, numbers and dashes.',
            'slug.unique' => 'Another publication already uses this URL slug.',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        foreach (['theme', 'year', 'size', 'summary'] as $k) {
            $data[$k] = isset($data[$k]) && trim($data[$k]) !== '' ? trim($data[$k]) : null;
        }

        return $data;
    }

    /** Store uploads under a brand-new unique name every time (Cloudflare caches media forever). */
    private function applyFiles(Request $request, array $data, ?Publication $publication): array
    {
        unset($data['pdf'], $data['cover']);

        foreach (['pdf' => 'pdf_path', 'cover' => 'cover_path'] as $input => $column) {
            if ($request->hasFile($input)) {
                $this->deleteUploaded($publication?->{$column});
                $data[$column] = $this->storeUnique($request->file($input));
            } elseif ($publication && $request->boolean("remove_{$input}")) {
                $this->deleteUploaded($publication->{$column});
                $data[$column] = null;
            }
        }

        return $data;
    }

    private function storeUnique(UploadedFile $file): string
    {
        // extension from the real content (validated image/pdf), never the client's filename ("x.php" with PDF bytes)
        $ext = strtolower($file->guessExtension() ?: 'bin');
        $name = now()->format('Ymd-His') . '-' . Str::lower(Str::random(10)) . '.' . $ext;
        $file->storeAs(self::DIR, $name, 'public');

        return 'storage/' . self::DIR . '/' . $name;
    }

    /** Only files uploaded through this module are ever deleted (never the legacy media/ PDFs). */
    private function deleteUploaded(?string $path): void
    {
        $prefix = 'storage/' . self::DIR . '/';
        if ($path && str_starts_with($path, $prefix)) {
            Storage::disk('public')->delete(self::DIR . '/' . substr($path, strlen($prefix)));
        }
    }
}
