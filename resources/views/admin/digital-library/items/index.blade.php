@extends('admin.layout')
@section('title', 'Digital Library')

@section('content')
<div class="flex items-center justify-between mb-5">
    <a href="{{ route('admin.digital-library.collections.index') }}"
       class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg px-3 py-1.5 bg-white">
        <span class="material-symbols-outlined" style="font-size:16px;">folder_open</span>
        Manage Collections
    </a>
    <a href="{{ route('admin.digital-library.items.create') }}"
       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold"
       style="background:#0a2540;">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
        Add Item
    </a>
</div>

@forelse($collections as $collection)
<div class="mb-8">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-gray-400" style="font-size:20px;">{{ $collection->icon }}</span>
        <h2 class="font-semibold text-gray-700 text-sm">{{ $collection->name }}</h2>
        <span class="text-xs text-gray-400">({{ $collection->items->count() }})</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                    <th class="text-center px-5 py-3 font-medium text-gray-600 hidden sm:table-cell">Type</th>
                    <th class="text-center px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collection->items as $item)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-900 leading-snug">{{ $item->title }}</div>
                        @if($item->url)
                            <div class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->url }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center hidden sm:table-cell">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $item->type === 'pdf' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                            {{ strtoupper($item->type) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <form method="POST" action="{{ route('admin.digital-library.items.toggle', $item) }}">
                            @csrf
                            <button type="submit"
                                    class="text-xs px-3 py-1 rounded-full font-medium transition-colors
                                           {{ $item->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $item->is_published ? 'Published' : 'Draft' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.digital-library.items.edit', $item) }}"
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.digital-library.items.destroy', $item) }}"
                                  onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-6 text-center text-gray-400 text-sm">
                        No items in this collection.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400 text-sm">
    No collections yet.
    <a href="{{ route('admin.digital-library.collections.create') }}" class="text-blue-600 hover:underline">Create a collection</a>
    first, then add items.
</div>
@endforelse
@endsection
