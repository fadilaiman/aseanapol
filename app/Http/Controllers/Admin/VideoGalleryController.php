<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    public function index()
    {
        $videos = VideoGallery::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(15);

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
            'embed_url'      => 'nullable|url|max:500',
            'thumbnail_url'  => 'nullable|url|max:500',
            'category'       => 'required|string|max:50',
            'event_location' => 'nullable|string|max:255',
            'event_date'     => 'nullable|date',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        VideoGallery::create($data);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video added successfully.');
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
            'embed_url'      => 'nullable|url|max:500',
            'thumbnail_url'  => 'nullable|url|max:500',
            'category'       => 'required|string|max:50',
            'event_location' => 'nullable|string|max:255',
            'event_date'     => 'nullable|date',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $videoGallery->update($data);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(VideoGallery $videoGallery)
    {
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
