@php
    $in = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500';
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" integrity="sha384-ecIckRi4QlKYya/FQUbBUjS4qp65jF/J87Guw5uzTbO1C1Jfa/6kYmd6dXUF6D7i" crossorigin="anonymous">
<style>#body-editor .ql-editor { min-height: 320px; font-size: 14px; }</style>
<div class="max-w-4xl">
    <a href="{{ route('admin.custom-pages.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to Custom Pages
    </a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5" data-testid="form-errors">
                <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ $action }}" class="space-y-5" id="page-form">
            @csrf
            @if($method !== 'POST') @method($method) @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="title">Title <span class="text-red-500">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $page->title) }}" maxlength="255" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="slug">Address (slug)</label>
                    <input id="slug" type="text" name="slug" value="{{ old('slug', $page->slug) }}" maxlength="120" placeholder="privacy-policy" class="{{ $in }}">
                    <p class="text-xs text-gray-400 mt-1">Lower-case letters, numbers and hyphens; empty = made from the title. Public URL: /en/page/&lt;slug&gt;. Footer link text: <code>page:&lt;slug&gt;</code>.</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <input type="hidden" id="body" name="body" value="{{ old('body', $page->body) }}">
                <div class="bg-white" id="body-editor"></div>
                <p class="text-xs text-gray-400 mt-1">Scripts are stripped on save.</p>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published ? 1 : 0)) class="w-4 h-4 rounded border-gray-300 text-blue-600">
                <span class="text-sm font-medium text-gray-700">Published (visitors get a 404 while this is off)</span>
            </label>
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.custom-pages.index') }}" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                <button type="submit" data-testid="save-page" class="px-5 py-2 text-sm font-semibold text-white rounded-lg" style="background:#0a2540;">{{ $submit }}</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js" integrity="sha384-utBUCeG4SYaCm4m7GQZYr8Hy8Fpy3V4KGjBZaf4WTKOcwhCYpt/0PfeEe3HNlwx8" crossorigin="anonymous"></script>
<script>
(function () {
    if (typeof Quill === 'undefined') return;
    var input = document.getElementById('body');
    var q = new Quill('#body-editor', { theme: 'snow', modules: { toolbar: [[{ header: [2, 3, 4, false] }], ['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['link', 'blockquote'], ['clean']] } });
    q.root.setAttribute('aria-label', 'Page content (rich text editor)');
    q.clipboard.dangerouslyPasteHTML(input.value || '');
    q.on('text-change', function (d, o, source) {
        if (source !== 'user') return;
        input.value = q.getText().trim() === '' ? '' : q.root.innerHTML;
    });
})();
</script>
