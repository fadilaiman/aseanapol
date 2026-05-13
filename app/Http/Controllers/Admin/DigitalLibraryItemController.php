<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalLibraryCollection;
use App\Models\DigitalLibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalLibraryItemController extends Controller
{
    public function index()
    {
        $collections = DigitalLibraryCollection::with(['items' => fn($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.digital-library.items.index', compact('collections'));
    }

    public function create()
    {
        $collections = DigitalLibraryCollection::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.digital-library.items.create', compact('collections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'collection_id' => 'required|exists:digital_library_collections,id',
            'title'         => 'required|string|max:255',
            'type'          => 'required|in:pdf,link',
            'external_url'  => 'nullable|url|max:500',
            'pdf_url'       => 'nullable|url|max:500',
            'file'          => 'nullable|file|mimes:pdf|max:51200',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('digital-library', 'public');
        }

        unset($data['file']);
        DigitalLibraryItem::create($data);

        return redirect()->route('admin.digital-library.items.index')
            ->with('success', 'Item added.');
    }

    public function edit(DigitalLibraryItem $item)
    {
        $collections = DigitalLibraryCollection::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.digital-library.items.edit', compact('item', 'collections'));
    }

    public function update(Request $request, DigitalLibraryItem $item)
    {
        $data = $request->validate([
            'collection_id' => 'required|exists:digital_library_collections,id',
            'title'         => 'required|string|max:255',
            'type'          => 'required|in:pdf,link',
            'external_url'  => 'nullable|url|max:500',
            'pdf_url'       => 'nullable|url|max:500',
            'file'          => 'nullable|file|mimes:pdf|max:51200',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('file')) {
            if ($item->file_path) {
                Storage::disk('public')->delete($item->file_path);
            }
            $data['file_path'] = $request->file('file')->store('digital-library', 'public');
        }

        unset($data['file']);
        $item->update($data);

        return redirect()->route('admin.digital-library.items.index')
            ->with('success', 'Item updated.');
    }

    public function destroy(DigitalLibraryItem $item)
    {
        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }

        $item->delete();

        return redirect()->route('admin.digital-library.items.index')
            ->with('success', 'Item removed.');
    }

    public function toggle(DigitalLibraryItem $item)
    {
        $item->update(['is_published' => !$item->is_published]);
        $status = $item->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Item {$status}.");
    }
}
