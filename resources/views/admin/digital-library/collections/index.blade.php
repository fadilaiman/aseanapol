@extends('admin.layout')
@section('title', 'Digital Library — Collections')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.digital-library.items.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
            Items
        </a>
        <span class="text-gray-300">|</span>
        <span class="text-sm text-gray-500">{{ $collections->count() }} collection(s)</span>
    </div>
    <a href="{{ route('admin.digital-library.collections.create') }}"
       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold"
       style="background:#0a2540;">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
        Add Collection
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Collection</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden sm:table-cell">Icon</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Items</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Visibility</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Order</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($collections as $collection)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-4 font-medium text-gray-900">{{ $collection->name }}
                    @if($collection->description)<div class="text-xs text-gray-400 font-normal mt-0.5">{{ Str::limit($collection->description, 80) }}</div>@endif
                </td>
                <td class="px-5 py-4 hidden sm:table-cell">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:20px;">{{ $collection->icon }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if($collection->items_count > 0)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-medium">{{ $collection->items_count }} {{ Str::plural('item', $collection->items_count) }}</span>
                    @else
                        <span class="text-xs text-gray-400">Empty</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $collection->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $collection->is_visible ? 'Visible' : 'Hidden' }}</span>
                </td>
                <td class="px-5 py-4 text-center text-gray-400 hidden md:table-cell">{{ $collection->sort_order }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.digital-library.collections.edit', $collection) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.digital-library.collections.destroy', $collection) }}"
                              onsubmit="return confirm({{ Js::from($collection->items_count > 0 ? 'WARNING: Deleting "' . $collection->name . '" will permanently delete its ' . $collection->items_count . ' item(s) and any uploaded PDFs. This cannot be undone. Continue?' : 'Delete the empty collection "' . $collection->name . '"?') }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                    No collections yet. <a href="{{ route('admin.digital-library.collections.create') }}" class="text-blue-600 hover:underline">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
