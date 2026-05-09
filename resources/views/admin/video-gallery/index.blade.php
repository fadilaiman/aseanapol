@extends('admin.layout')
@section('title', 'Video Gallery')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="text-sm text-gray-500">{{ $videos->total() }} item(s)</div>
    <a href="{{ route('admin.video-gallery.create') }}"
       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold transition-colors"
       style="background:#0a2540;">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
        Add Video
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Category</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Date</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($videos as $video)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-4">
                    <div class="font-medium text-gray-900 leading-snug">{{ $video->title }}</div>
                    @if($video->description)
                        <div class="text-gray-400 text-xs mt-0.5 truncate max-w-xs">{{ Str::limit($video->description, 60) }}</div>
                    @endif
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $video->category }}</span>
                </td>
                <td class="px-5 py-4 text-gray-500 hidden lg:table-cell">
                    {{ $video->event_date ? $video->event_date->format('M Y') : '—' }}
                </td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.video-gallery.toggle', $video) }}">
                        @csrf
                        <button type="submit"
                                class="text-xs px-3 py-1 rounded-full font-medium transition-colors
                                       {{ $video->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $video->is_published ? 'Published' : 'Draft' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.video-gallery.edit', $video) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.video-gallery.destroy', $video) }}"
                              onsubmit="return confirm('Remove this video?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                    No videos yet. <a href="{{ route('admin.video-gallery.create') }}" class="text-blue-600 hover:underline">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($videos->hasPages())
    <div class="mt-4">{{ $videos->links() }}</div>
@endif
@endsection
