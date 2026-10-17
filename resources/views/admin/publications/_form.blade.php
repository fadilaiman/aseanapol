@php
    $inp = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500';
    $file = 'w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200';
@endphp

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5" data-testid="form-errors">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $publication->title) }}" required class="{{ $inp }}"
           placeholder="16th Edition ASEANAPOL Bulletin">
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Edition <span class="text-red-500">*</span></label>
        <input type="text" name="edition" value="{{ old('edition', $publication->edition) }}" required class="{{ $inp }}" placeholder="16th">
        <p class="text-xs text-gray-400 mt-1">Big number on the cover.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
        <select name="kind" class="{{ $inp }}">
            @foreach(['bulletin' => 'Bulletin', 'magazine' => 'Magazine'] as $v => $l)
                <option value="{{ $v }}" @selected(old('kind', $publication->kind) === $v)>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
        <input type="text" name="year" value="{{ old('year', $publication->year) }}" class="{{ $inp }}" placeholder="2027">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Theme</label>
    <input type="text" name="theme" value="{{ old('theme', $publication->theme) }}" class="{{ $inp }}">
    <p class="text-xs text-gray-400 mt-1">Shown in italics on the detail page. Leave empty for none.</p>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="summary" rows="3" class="{{ $inp }} resize-none">{{ old('summary', $publication->summary) }}</textarea>
    <p class="text-xs text-gray-400 mt-1">Optional. Replaces the standard introduction paragraph on this edition's detail page.</p>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">URL slug</label>
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-400 whitespace-nowrap">/publication/</span>
        <input type="text" name="slug" value="{{ old('slug', $publication->slug) }}" class="{{ $inp }}" placeholder="auto from title">
    </div>
    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and dashes. Leave empty to generate from the title. Changing it breaks old links to this edition.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">PDF file</label>
        @if($publication->pdf_path)
            <div class="flex items-center gap-2 mb-2 text-sm text-gray-600 flex-wrap">
                <span class="material-symbols-outlined text-green-600" style="font-size:16px;">check_circle</span>
                Current:
                <a href="{{ $publication->pdf_url }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($publication->pdf_path) }}</a>
            </div>
            <label class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <input type="checkbox" name="remove_pdf" value="1" class="w-4 h-4 rounded border-gray-300"> Remove current PDF
            </label>
        @endif
        <input type="file" name="pdf" accept=".pdf,application/pdf" class="{{ $file }}">
        <p class="text-xs text-gray-400 mt-1">PDF, max 500 MB. A new upload gets a new file name and replaces the current file.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">File size label</label>
        <input type="text" name="size" value="{{ old('size', $publication->size) }}" class="{{ $inp }}" placeholder="12 MB">
        <p class="text-xs text-gray-400 mt-1">Shown next to the download button, e.g. "373 MB". Optional.</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover image</label>
        @if($publication->cover_path)
            <div class="flex items-center gap-3 mb-2">
                <img src="{{ $publication->cover_url }}" alt="" class="w-14 h-20 object-cover rounded border border-gray-200">
                <label class="flex items-center gap-2 text-xs text-gray-500">
                    <input type="checkbox" name="remove_cover" value="1" class="w-4 h-4 rounded border-gray-300"> Remove cover
                </label>
            </div>
        @endif
        <input type="file" name="cover" accept="image/png,image/jpeg,image/webp" class="{{ $file }}">
        <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP, max 5 MB. Optional &mdash; without it the coloured cover is used.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover colour</label>
        <select name="color" class="{{ $inp }}">
            @foreach(\App\Models\Publication::COLORS as $cls => $label)
                <option value="{{ $cls }}" @selected(old('color', $publication->color) === $cls)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="mt-2 flex flex-wrap gap-1.5">
            @foreach(\App\Models\Publication::COLORS as $cls => $label)
                <span title="{{ $label }}" class="w-6 h-6 rounded" style="background:linear-gradient(135deg,{{ \App\Models\Publication::SWATCH[$cls][0] }},{{ \App\Models\Publication::SWATCH[$cls][1] }})"></span>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $publication->sort_order) }}" min="0" class="{{ $inp }}">
        <p class="text-xs text-gray-400 mt-1">Lowest number is listed first.</p>
    </div>
    <div class="flex items-end pb-3">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $publication->is_published) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm font-medium text-gray-700">Published</span>
        </label>
    </div>
</div>
