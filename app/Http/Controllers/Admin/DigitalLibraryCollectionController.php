<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalLibraryCollection;
use Illuminate\Http\Request;

class DigitalLibraryCollectionController extends Controller
{
    public function index()
    {
        $collections = DigitalLibraryCollection::withCount('items')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.digital-library.collections.index', compact('collections'));
    }

    public function create()
    {
        return view('admin.digital-library.collections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        DigitalLibraryCollection::create($data);

        return redirect()->route('admin.digital-library.collections.index')
            ->with('success', 'Collection created.');
    }

    public function edit(DigitalLibraryCollection $collection)
    {
        return view('admin.digital-library.collections.edit', compact('collection'));
    }

    public function update(Request $request, DigitalLibraryCollection $collection)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        $collection->update($data);

        return redirect()->route('admin.digital-library.collections.index')
            ->with('success', 'Collection updated.');
    }

    public function destroy(DigitalLibraryCollection $collection)
    {
        $collection->delete();

        return redirect()->route('admin.digital-library.collections.index')
            ->with('success', 'Collection and its items removed.');
    }
}
