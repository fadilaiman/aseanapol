@extends('admin.layout')
@section('title', 'News')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <form method="GET" action="{{ route('admin.news.index') }}" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search title or author…"
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 w-64 focus:outline-none focus:ring-2 focus:ring-blue-300">
        <button type="submit" class="px-3 py-2 rounded-lg text-sm text-white font-medium" style="background:#0a2540;">Search</button>
        @if($q)
            <a href="{{ route('admin.news.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
        @endif
    </form>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ $articles->total() }} article(s)</span>
        <a href="{{ route('admin.news.create') }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold transition-colors"
           style="background:#0a2540;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Add Article
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Author</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Published</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($articles as $article)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900 leading-snug line-clamp-2 max-w-md">{{ $article->title }}</div>
                    @if($article->summary)
                        <div class="text-gray-400 text-xs mt-0.5 line-clamp-1">{{ $article->summary }}</div>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-600 hidden md:table-cell">{{ $article->author ?: '—' }}</td>
                <td class="px-5 py-3 text-gray-500 hidden lg:table-cell">
                    {{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.news.edit', $article) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.news.destroy', $article) }}"
                              onsubmit="return confirm('Delete this article?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">
                    No articles found. <a href="{{ route('admin.news.create') }}" class="text-blue-600 hover:underline">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($articles->hasPages())
    <div class="mt-4">{{ $articles->links() }}</div>
@endif
@endsection
