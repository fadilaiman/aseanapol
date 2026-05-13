@extends('admin.layout')
@section('title', 'Add Library Item')

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

        @if($collections->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm px-4 py-3 rounded-lg mb-5">
                You need to <a href="{{ route('admin.digital-library.collections.create') }}" class="font-semibold underline">create a collection</a> first.
            </div>
        @endif

        <form method="POST" action="{{ route('admin.digital-library.items.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collection <span class="text-red-500">*</span></label>
                <select name="collection_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    <option value="">— Select collection —</option>
                    @foreach($collections as $col)
                        <option value="{{ $col->id }}" {{ old('collection_id') == $col->id ? 'selected' : '' }}>
                            {{ $col->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="pdf" {{ old('type', 'pdf') === 'pdf' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600" id="type_pdf">
                        <span class="text-sm text-gray-700">PDF Document</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="link" {{ old('type') === 'link' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600" id="type_link">
                        <span class="text-sm text-gray-700">Web Link</span>
                    </label>
                </div>
            </div>

            <div id="pdf_section">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload PDF</label>
                <input type="file" name="file" accept=".pdf"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-400 mt-1">PDF only, max 50 MB. Or provide an external URL below instead.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span id="url_label">External PDF URL or Web Link URL</span>
                </label>
                <input type="url" name="external_url" value="{{ old('external_url') }}"
                       placeholder="https://..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">For PDFs: use if the file is hosted externally. For links: the destination URL.</p>
            </div>

            <div id="pdf_url_section" style="display:none">
                <label class="block text-sm font-medium text-gray-700 mb-1">PDF Download URL <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="url" name="pdf_url" value="{{ old('pdf_url') }}"
                       placeholder="https://... (link to downloadable PDF)"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">If set, a "Download PDF" button will appear alongside the "View" link on the public site.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                </div>
                <div class="flex items-end pb-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600">
                        <span class="text-sm font-medium text-gray-700">Publish immediately</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.digital-library.items.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:text-gray-800">Cancel</a>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg"
                        style="background:#0a2540;">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    const pdfSection = document.getElementById('pdf_section');
    const pdfUrlSection = document.getElementById('pdf_url_section');
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            pdfSection.style.display = radio.value === 'pdf' ? 'block' : 'none';
            pdfUrlSection.style.display = radio.value === 'link' ? 'block' : 'none';
        });
    });
    // Init
    const checked = document.querySelector('input[name="type"]:checked');
    if (checked) {
        pdfSection.style.display = checked.value === 'pdf' ? 'block' : 'none';
        pdfUrlSection.style.display = checked.value === 'link' ? 'block' : 'none';
    }
</script>
@endsection
