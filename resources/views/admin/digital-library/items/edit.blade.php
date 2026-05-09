@extends('admin.layout')
@section('title', 'Edit Library Item')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.digital-library.items.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to Digital Library
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.digital-library.items.update', $item) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collection <span class="text-red-500">*</span></label>
                <select name="collection_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    @foreach($collections as $col)
                        <option value="{{ $col->id }}" {{ old('collection_id', $item->collection_id) == $col->id ? 'selected' : '' }}>
                            {{ $col->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="pdf" {{ old('type', $item->type) === 'pdf' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600">
                        <span class="text-sm text-gray-700">PDF Document</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="link" {{ old('type', $item->type) === 'link' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600">
                        <span class="text-sm text-gray-700">Web Link</span>
                    </label>
                </div>
            </div>

            <div id="pdf_section" style="{{ old('type', $item->type) === 'pdf' ? '' : 'display:none' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload PDF</label>
                @if($item->file_path)
                    <div class="flex items-center gap-2 mb-2 text-sm text-gray-600">
                        <span class="material-symbols-outlined text-green-600" style="font-size:16px;">check_circle</span>
                        Current file:
                        <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ basename($item->file_path) }}
                        </a>
                    </div>
                @endif
                <input type="file" name="file" accept=".pdf"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-400 mt-1">Upload to replace the existing file. PDF only, max 50 MB.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">External URL</label>
                <input type="url" name="external_url" value="{{ old('external_url', $item->external_url) }}"
                       placeholder="https://..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">For PDFs: external host URL. For links: destination URL.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                </div>
                <div class="flex items-end pb-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1"
                               {{ old('is_published', $item->is_published) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600">
                        <span class="text-sm font-medium text-gray-700">Published</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.digital-library.items.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:text-gray-800">Cancel</a>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg"
                        style="background:#0a2540;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    const pdfSection = document.getElementById('pdf_section');
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            pdfSection.style.display = radio.value === 'pdf' ? 'block' : 'none';
        });
    });
</script>
@endsection
