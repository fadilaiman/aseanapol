<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    public function index()
    {
        $videos = VideoGallery::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(15); // same order as the public page

        return view('admin.video-gallery.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.video-gallery.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'embed_url'      => 'nullable|url:http,https|max:500',
            'file_url'       => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'thumbnail_url'  => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'category'       => 'required|string|max:50',
            'event_location' => 'nullable|string|max:255',
            'event_date'     => 'nullable|date',
            'sort_order'     => 'nullable|integer|min:0|max:9999',
            'video_file'     => 'nullable|file|mimes:mp4,webm|max:102400',
            'thumbnail_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], $this->messages());
        unset($data['video_file'], $data['thumbnail_file']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        foreach ([['video_file', 'file_url', 'media/videos'], ['thumbnail_file', 'thumbnail_url', 'media/videos/posters']] as [$field, $col, $dir]) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $data[$col] = $this->storeUpload($request->file($field), $dir);
            }
        }

        $data['is_published'] = $request->boolean('is_published');
        VideoGallery::create($data);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video added successfully.');
    }

    private function messages(): array
    {
        $limit = ini_get('upload_max_filesize') . ' per file, ' . ini_get('post_max_size') . ' per request';
        return [
            'video_file.mimes'        => 'The video must be an MP4 or WebM file.',
            'video_file.max'          => 'The video is too large. Maximum size is 100 MB.',
            'video_file.uploaded'     => "The video could not be uploaded: it is larger than the server upload limit ($limit). Use a smaller file or ask the administrator to raise the limit.",
            'thumbnail_file.mimes'    => 'The thumbnail must be a JPG, PNG or WebP image.',
            'thumbnail_file.max'      => 'The thumbnail is too large. Maximum size is 5 MB.',
            'thumbnail_file.uploaded' => "The thumbnail could not be uploaded: it is larger than the server upload limit ($limit).",
        ];
    }

    /** Save under public/<dir> with a unique, sanitized name; returns the relative path stored in the DB. */
    private function storeUpload(\Illuminate\Http\UploadedFile $file, string $dir): string
    {
        $base = \Illuminate\Support\Str::limit(\Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 60, '') ?: 'file';
        $name = $base . '-' . now()->format('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . ($file->extension() ?: $file->guessExtension());
        $file->move(public_path($dir), $name);

        return $dir . '/' . $name;
    }

    public function edit(VideoGallery $videoGallery)
    {
        return view('admin.video-gallery.edit', compact('videoGallery'));
    }

    public function update(Request $request, VideoGallery $videoGallery)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'embed_url'      => 'nullable|url:http,https|max:500',
            'file_url'       => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'thumbnail_url'  => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/|media/|storage/)\S+$#i'],
            'category'       => 'required|string|max:50',
            'event_location' => 'nullable|string|max:255',
            'event_date'     => 'nullable|date',
            'sort_order'     => 'nullable|integer|min:0|max:9999',
            'video_file'     => 'nullable|file|mimes:mp4,webm|max:102400',
            'thumbnail_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], $this->messages());
        unset($data['video_file'], $data['thumbnail_file']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        foreach ([['video_file', 'file_url', 'media/videos'], ['thumbnail_file', 'thumbnail_url', 'media/videos/posters']] as [$field, $col, $dir]) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $data[$col] = $this->storeUpload($request->file($field), $dir);
            }
        }

        $data['is_published'] = $request->boolean('is_published');
        foreach (['file_url', 'thumbnail_url'] as $col) {
            if (($data[$col] ?? null) !== $videoGallery->{$col}) {
                $this->deleteUploaded($videoGallery->{$col});
            }
        }
        $videoGallery->update($data);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video updated successfully.');
    }

    /** Delete a file only if it lives in our upload dirs (never seeded/external paths). */
    private function deleteUploaded(?string $path): void
    {
        if ($path && preg_match('#^media/videos/(posters/)?[A-Za-z0-9._-]+-\d{8}-\d{6}-[0-9a-f]{6}\.[a-z0-9]+$#', $path)) {
            @unlink(public_path($path));
        }
    }

    public function destroy(VideoGallery $videoGallery)
    {
        $this->deleteUploaded($videoGallery->file_url);
        $this->deleteUploaded($videoGallery->thumbnail_url);
        $videoGallery->delete();

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video removed.');
    }

    public function toggle(VideoGallery $videoGallery)
    {
        $videoGallery->update(['is_published' => !$videoGallery->is_published]);
        $status = $videoGallery->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Video {$status}.");
    }
}
