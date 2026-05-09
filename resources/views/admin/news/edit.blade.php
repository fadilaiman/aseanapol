@extends('admin.layout')
@section('title', 'Edit Article')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to News
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.news.update', $news) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $news->title) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                    <input type="text" name="author" value="{{ old('author', $news->author) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Published Date</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                <input type="url" name="thumbnail" value="{{ old('thumbnail', $news->thumbnail) }}"
                       placeholder="https://…"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                @if($news->thumbnail)
                    <img src="{{ $news->thumbnail }}" alt="thumbnail" class="mt-2 h-20 rounded-lg object-cover">
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                <textarea name="summary" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('summary', $news->summary) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="10"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">{{ old('content', $news->content) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $news->slug) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-gray-400">{{ $news->views_count }} views &middot; ID: {{ $news->id }}</div>
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-white text-sm font-semibold"
                        style="background:#0a2540;">Update Article</button>
            </div>
        </form>
    </div>
</div>
@endsection
