@extends('admin.layout')
@section('title', 'Add Article')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to News
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                    <input type="text" name="author" value="{{ old('author') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Published Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
                <div class="flex gap-1 mb-3">
                    <button type="button" onclick="switchTab('url')" id="tab-url"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors bg-gray-900 text-white border-gray-900">
                        External URL
                    </button>
                    <button type="button" onclick="switchTab('file')" id="tab-file"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors bg-white text-gray-500 border-gray-200 hover:bg-gray-50">
                        Upload File
                    </button>
                </div>
                <div id="panel-url">
                    <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url') }}"
                           placeholder="https://…"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div id="panel-file" class="hidden">
                    <input type="file" name="thumbnail_file" accept="image/*"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                <textarea name="summary" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="10"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-gray-400 font-normal">(auto-generated if blank)</span></label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_upcoming_event" id="is_upcoming_event" value="1" {{ old('is_upcoming_event') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-300">
                <label for="is_upcoming_event" class="text-sm font-medium text-gray-700">Show "Upcoming Event" badge</label>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-white text-sm font-semibold"
                        style="background:#0a2540;">Save Article</button>
            </div>
        </form>
    </div>
</div>
<script>
function switchTab(tab) {
    document.getElementById('panel-url').classList.toggle('hidden', tab !== 'url');
    document.getElementById('panel-file').classList.toggle('hidden', tab !== 'file');
    ['url','file'].forEach(t => {
        const btn = document.getElementById('tab-' + t);
        const active = t === tab;
        btn.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors '
            + (active ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50');
    });
}
</script>
@endsection
