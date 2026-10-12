@extends('admin.layout')
@section('title', 'Publications')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div class="text-sm text-gray-500">
        {{ $publications->count() }} edition(s) &middot; shown on the public page in this order (lowest sort order first)
    </div>
    <a href="{{ route('admin.publications.create') }}" data-testid="add-publication"
       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold transition-colors"
       style="background:#0a2540;">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
        Add Publication
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm min-w-[520px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-center px-4 py-3 font-medium text-gray-600 w-16">Order</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Type</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Year</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600 hidden md:table-cell">PDF</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($publications as $pub)
            <tr class="hover:bg-gray-50/50" data-pub="{{ $pub->slug }}">
                <td class="px-4 py-4 text-center text-gray-500">{{ $pub->sort_order }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        @if($pub->cover_url)
                            <img src="{{ $pub->cover_url }}" alt="" class="w-9 h-12 rounded object-cover border border-gray-200 shrink-0">
                        @else
                            <div class="w-9 h-12 rounded flex shrink-0" style="{{ $pub->swatch_style }}" data-x="1"><span class="m-auto flex items-center justify-center text-white text-[10px] font-bold">{{ $pub->edition }}</span></div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-medium text-gray-900 leading-snug break-words">{{ $pub->title }}</div>
                            <a href="{{ route('publication.show', ['locale' => 'en', 'slug' => $pub->slug]) }}" target="_blank"
                               class="text-xs text-gray-400 hover:text-blue-600">/publication/{{ $pub->slug }}</a>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $pub->type }}</td>
                <td class="px-5 py-4 text-gray-500 hidden lg:table-cell">{{ $pub->year ?: '—' }}</td>
                <td class="px-5 py-4 text-center hidden md:table-cell">
                    @if($pub->pdf_url)
                        <a href="{{ $pub->pdf_url }}" target="_blank"
                           class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800">
                            <span class="material-symbols-outlined" style="font-size:14px;">download</span>
                            PDF{{ $pub->size ? ' · ' . $pub->size : '' }}
                        </a>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.publications.toggle', $pub) }}">
                        @csrf
                        <button type="submit" data-testid="toggle"
                                class="text-xs px-3 py-1 rounded-full font-medium transition-colors
                                       {{ $pub->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $pub->is_published ? 'Published' : 'Hidden' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.publications.edit', $pub) }}" data-testid="edit"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.publications.destroy', $pub) }}"
                              onsubmit="return confirm('Remove this publication? Its uploaded files are deleted too.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-testid="delete" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                    No publications yet. <a href="{{ route('admin.publications.create') }}" class="text-blue-600 hover:underline">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="text-xs text-gray-400 mt-4">
    Page headings, intro text and button labels are edited under
    <a href="{{ route('admin.cms.index') }}" class="text-blue-600 hover:underline">Site Content &rarr; Data &amp; Resources</a>.
</p>
@endsection
