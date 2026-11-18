@extends('admin.layout')
@section('title', 'Photo Gallery')

@php
    $inputCls = 'border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 bg-white';
    $hasFilters = array_filter($filters, fn ($v) => $v !== '' && $v !== null);
    $sourceOf = function ($p) {
        return match (true) {
            str_starts_with($p, 'media/gallery/') => 'Uploaded here',
            str_starts_with($p, 'media/news/facebook/') => 'Facebook post',
            str_starts_with($p, 'media/news/') => 'News article',
            default => 'Album folder',
        };
    };
@endphp

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="text-sm text-gray-500" data-testid="gallery-total">{{ $photos->total() }} photo(s) @if($hiddenCount)&middot; {{ $hiddenCount }} hidden @endif</div>
    <a href="{{ route('news-media.gallery', ['locale' => 'en']) }}" target="_blank" rel="noopener" class="px-3 py-2 rounded-lg text-sm text-gray-600 border border-gray-300 hover:bg-gray-50">View live gallery</a>
</div>

<div class="bg-blue-50 border border-blue-200 text-blue-900 text-sm px-4 py-3 rounded-lg mb-4">
    The public gallery is built automatically from news articles, Facebook posts and album folders (refreshed every 15 minutes).
    You can <strong>hide</strong> any photo, <strong>upload</strong> extra photos into an album, and <strong>delete</strong> photos you uploaded.
    <strong>Delete on a news/album photo only hides it</strong> from the gallery: the file and its news article are not touched, and you can restore it with the "Hidden" filter.
</div>

@if($errors->any())
    <div role="alert" class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4" data-testid="form-errors">
        <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<details class="bg-white rounded-xl border border-gray-200 mb-4" data-testid="upload-box" @if($errors->any()) open @endif>
    <summary class="px-5 py-3 cursor-pointer text-sm font-semibold text-gray-700">Upload photos</summary>
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="px-5 pb-5 pt-2 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        @csrf
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-500 mb-1" for="photos">Photos (JPG, PNG, WebP, GIF; up to 30, 8 MB each)</label>
            <input id="photos" type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="text-sm w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="album_key">Album</label>
            <select id="album_key" name="album_key" class="w-full {{ $inputCls }}">
                @foreach($albums as $k => $label)<option value="{{ $k }}" @selected(old('album_key', 'activities') === $k)>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="event_date">Event date</label>
            <input id="event_date" type="date" name="event_date" max="{{ now()->toDateString() }}" value="{{ old('event_date', now()->toDateString()) }}" class="w-full {{ $inputCls }}">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1" for="caption">Caption (optional; used as the picture's description)</label>
            <input id="caption" type="text" name="caption" maxlength="255" value="{{ old('caption') }}" class="w-full {{ $inputCls }}">
        </div>
        <div>
            <button type="submit" data-testid="upload-photos" class="px-5 py-2 rounded-lg text-sm font-semibold text-white" style="background:#0a2540;">Upload</button>
        </div>
    </form>
</details>

<form method="GET" action="{{ route('admin.gallery.index') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3" data-testid="filters">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
        <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="File name or caption…" class="w-full {{ $inputCls }}">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Album</label>
        <select name="album" class="{{ $inputCls }}">
            <option value="">All albums</option>
            @foreach($albums as $k => $label)<option value="{{ $k }}" @selected($filters['album'] === $k)>{{ $label }}</option>@endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Show</label>
        <select name="status" class="{{ $inputCls }}">
            <option value="">Visible</option>
            <option value="hidden" @selected($filters['status'] === 'hidden')>Hidden</option>
            <option value="all" @selected($filters['status'] === 'all')>Visible and hidden</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Source</label>
        <select name="source" class="{{ $inputCls }}">
            <option value="">Any</option>
            <option value="uploaded" @selected($filters['source'] === 'uploaded')>Uploaded here</option>
            <option value="indexed" @selected($filters['source'] === 'indexed')>News / album folders</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#0a2540;">Filter</button>
        @if($hasFilters)<a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 rounded-lg text-sm text-gray-600 border border-gray-300 hover:bg-gray-50">Reset</a>@endif
    </div>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm" data-testid="gallery-table">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Photo</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Album</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Date</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Source</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($photos as $p)
            <tr class="hover:bg-gray-50/50 {{ $p->status === 'hidden' ? 'opacity-60' : '' }}" data-row-path="{{ $p->path }}">
                <td class="px-5 py-2">
                    <div class="flex items-center gap-3">
                        <a href="{{ asset($p->path) }}" target="_blank" rel="noopener"><img src="{{ asset($p->path) }}" alt="{{ $p->caption ?: basename($p->path) }}" loading="lazy" class="w-16 h-16 object-cover rounded-lg bg-gray-100"></a>
                        <div class="min-w-0">
                            <div class="text-gray-800 truncate max-w-[16rem]" title="{{ $p->path }}">{{ basename($p->path) }}</div>
                            @if($p->caption)<div class="text-xs text-gray-500 truncate max-w-[16rem]">{{ $p->caption }}</div>@endif
                            @if($p->status === 'hidden')<span class="text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">Hidden</span>@endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-2 hidden md:table-cell text-gray-600">{{ $albums[$p->album_key] ?? $p->album_key }}</td>
                <td class="px-5 py-2 hidden md:table-cell text-gray-500 whitespace-nowrap">{{ $p->event_date ? \Illuminate\Support\Carbon::parse($p->event_date)->format('d M Y') : '—' }}</td>
                <td class="px-5 py-2 hidden lg:table-cell text-gray-500">{{ $sourceOf($p->path) }}</td>
                <td class="px-5 py-2 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <form method="POST" action="{{ route('admin.gallery.toggle') }}">
                            @csrf <input type="hidden" name="path" value="{{ $p->path }}">
                            <button type="submit" data-testid="toggle-photo" class="text-xs font-medium text-blue-600 hover:text-blue-800">{{ $p->status === 'hidden' ? 'Unhide' : 'Hide' }}</button>
                        </form>
                        @php $isUp = str_starts_with($p->path, 'media/gallery/'); @endphp
                        <form method="POST" action="{{ route('admin.gallery.destroy') }}" onsubmit="return confirm('{{ $isUp ? 'Delete this uploaded photo permanently?' : 'This photo comes from a news article or album folder. Delete will only hide it from the gallery (the file and article stay). Continue?' }}')">
                            @csrf @method('DELETE') <input type="hidden" name="path" value="{{ $p->path }}">
                            <button type="submit" data-testid="delete-photo" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">No photos match.@if($hasFilters) <a href="{{ route('admin.gallery.index') }}" class="text-blue-600 hover:underline">Reset filters.</a>@endif</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($photos->hasPages())<div class="mt-4" data-testid="pagination">{{ $photos->links() }}</div>@endif
@endsection
