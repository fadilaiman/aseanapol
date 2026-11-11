@extends('admin.layout')
@section('title', 'Edit Article')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to News
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $news->title) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
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

            @include('admin.news._images', ['existingImages' => $news->galleryImages()])

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                <textarea name="summary" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('summary', $news->summary) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="10"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">{{ old('content', $news->contentForEditing()) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Just type or paste plain text &mdash; no HTML needed. Leave a blank line between paragraphs, and start a line with <code class="bg-gray-100 px-1 rounded">*</code> or <code class="bg-gray-100 px-1 rounded">-</code> to make a bullet point.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $news->slug) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">
            </div>

            @include('admin.news._sponsors')

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_upcoming_event" id="is_upcoming_event" value="1" {{ old('is_upcoming_event', $news->is_upcoming_event) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-300">
                <label for="is_upcoming_event" class="text-sm font-medium text-gray-700">Show "Upcoming Event" badge</label>
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
