@extends('admin.layout')
@section('title', $def['label'] . ' — Page Content')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
<style>
    .cms-rt .ql-editor { min-height: 120px; font-size: 14px; }
    .cms-section-hidden .cms-section-body { opacity: .45; }
</style>

<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div class="text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="text-blue-700 hover:underline">Site Content</a>
        <span class="mx-1">/</span>{{ $group['label'] }}
        <span class="mx-1">/</span><span class="text-gray-800 font-medium">{{ $def['label'] }}</span>
        @if($customized)<span class="ml-2 text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2 py-0.5">{{ $customized }} customised</span>@endif
    </div>
    <div class="flex items-center gap-2">
        @if($liveUrl)
            <a href="{{ $liveUrl }}" target="_blank" rel="noopener" data-testid="view-live" class="inline-flex items-center gap-1 px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                <span class="material-symbols-outlined" style="font-size:16px;">open_in_new</span> View live page
            </a>
        @endif
        <button type="submit" form="cms-reset-form" data-confirm="Revert ALL content on this page to the original?" data-testid="reset-page"
                class="px-3 py-2 text-xs rounded-lg border border-red-300 text-red-700 hover:bg-red-50">Revert page to original</button>
    </div>
</div>

@if($errors->any())
    <div role="alert" data-testid="cms-error" class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">{{ $errors->first() }}</div>
@endif
@if(!empty($conflict))
    <div role="alert" data-testid="cms-conflict" class="bg-amber-50 border border-amber-300 text-amber-900 text-sm px-4 py-3 rounded-lg mb-5">
        <strong>Not saved &mdash; this page was changed by someone else</strong> after you opened it.
        Your edits are shown below but have NOT been saved yet. Press <em>Save changes</em> to overwrite their version with yours,
        or <a class="underline" href="{{ route('admin.cms.edit', $pageKey) }}">reload</a> to see their changes (your edits will be lost).
        Files you selected for upload must be chosen again.
    </div>
@endif

{{-- Section jump list --}}
@if(count($def['sections']) > 1)
<nav class="flex flex-wrap gap-2 mb-5 text-xs">
    @foreach($def['sections'] as $sKey => $section)
        <a href="#sec-{{ $sKey }}" class="px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600 hover:border-blue-300">{{ $section['label'] }}</a>
    @endforeach
</nav>
@endif

<form id="cms-form" method="POST" action="{{ route('admin.cms.update', $pageKey) }}" enctype="multipart/form-data" class="space-y-5 pb-24">
    @csrf
    @method('PUT')
    <input type="hidden" name="_fp" value="{{ $fingerprint }}">

    @foreach($def['sections'] as $sKey => $section)
        @php $isHidden = in_array($sKey, $hidden, true); @endphp
        <section id="sec-{{ $sKey }}" class="bg-white rounded-xl border border-gray-200 {{ $isHidden ? 'cms-section-hidden' : '' }}" data-section="{{ $sKey }}">
            <header class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-b border-gray-100">
                <div>
                    <h2 class="font-semibold text-gray-800 text-sm">{{ $section['label'] }}</h2>
                    @if(!empty($section['help']))<p class="text-xs text-gray-400">{{ $section['help'] }}</p>@endif
                </div>
                <div class="flex items-center gap-4">
                    @if(!empty($section['toggle']))
                        <input type="hidden" name="vis[{{ $sKey }}]" value="0">
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-gray-700 cursor-pointer">
                            <input type="checkbox" name="vis[{{ $sKey }}]" value="1" @checked(!$isHidden) data-testid="vis-{{ $sKey }}" class="rounded border-gray-300">
                            Show this section on the page
                        </label>
                    @endif
                    <button type="submit" form="cms-reset-form" name="section" value="{{ $sKey }}" data-confirm="Revert this section to the original content?"
                            class="text-xs text-gray-500 hover:text-red-700 underline">Revert section</button>
                </div>
            </header>
            <div class="cms-section-body p-5 grid grid-cols-1 gap-5 {{ ($section['columns'] ?? 1) > 1 ? 'md:grid-cols-' . $section['columns'] : '' }}">
                @foreach($section['fields'] as $fKey => $field)
                    @php $full = in_array($field['type'] ?? 'text', ['textarea', 'richtext', 'html', 'repeater', 'list'], true); @endphp
                    <div class="{{ $full ? 'md:col-span-full' : '' }}">
                        @include('admin.cms._field', ['def' => $field, 'segs' => [$sKey, $fKey], 'value' => $values["$sKey.$fKey"] ?? null, 'depth' => 0])
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

    <div class="fixed bottom-0 right-0 left-0 md:left-60 bg-white/95 backdrop-blur border-t border-gray-200 px-4 md:px-6 py-3 flex items-center justify-between gap-3 z-20">
        <span class="text-xs text-gray-500 hidden sm:inline">Changes go live immediately after saving. Anything left identical to the original is not stored.</span>
        <span data-testid="dirty-flag" class="text-xs text-amber-700 font-medium hidden">Unsaved changes</span>
        <button type="submit" data-testid="save-page" class="px-5 py-2 rounded-lg text-white text-sm font-semibold ml-auto" style="background:#0a2540;">Save changes</button>
    </div>
    {{-- Must stay the LAST field of the form: proves the browser input was not truncated by PHP max_input_vars. --}}
    <input type="hidden" name="_end" value="1">
</form>

<form id="cms-reset-form" method="POST" action="{{ route('admin.cms.reset', $pageKey) }}">@csrf</form>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
(function () {
    var form = document.getElementById('cms-form');
    var dirty = false, uid = 0;
    var flag = document.querySelector('[data-testid=dirty-flag]');
    function markDirty() {
        dirty = true;
        form.dataset.dirty = '1';
        if (flag) flag.classList.remove('hidden');
    }
    function clean() { dirty = false; }
    form.addEventListener('input', markDirty);
    form.addEventListener('change', markDirty);
    form.addEventListener('submit', clean);
    document.getElementById('cms-reset-form').addEventListener('submit', clean);
    // Browsers ask "Leave site?" only when something was edited. Skipped under automation (would block navigation).
    if (!navigator.webdriver) {
        window.addEventListener('beforeunload', function (e) { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
    }

    // Row templates carry ids derived from the placeholder name, which are identical for every added row.
    function uniquify(node) {
        node.querySelectorAll('[id]').forEach(function (el) {
            var old = el.id, id = old + '-n' + (++uid);
            el.id = id;
            node.querySelectorAll('label[for="' + old + '"]').forEach(function (l) { l.htmlFor = id; });
            node.querySelectorAll('[data-target="' + old + '"]').forEach(function (t) { t.dataset.target = id; });
        });
    }

    function initEditors(root) {
        if (typeof Quill === 'undefined') return;
        root.querySelectorAll('.cms-rt:not([data-init])').forEach(function (el) {
            el.dataset.init = '1';
            // sibling lookup: ids of rows added from <template> are not unique until rewritten below
            var input = el.parentNode.querySelector('input[type=hidden]') || document.getElementById(el.dataset.target);
            var q = new Quill(el, {
                theme: 'snow',
                modules: { toolbar: [[{ header: [2, 3, 4, false] }], ['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['link', 'blockquote'], ['clean']] }
            });
            var lab = el.closest('.cms-field'); lab = lab && lab.querySelector('label');
            q.root.setAttribute('aria-label', (lab ? lab.textContent.trim() : 'Rich text') + ' (rich text editor)');
            q.clipboard.dangerouslyPasteHTML(input.value || '');
            q.on('text-change', function (d, o, source) {
                if (source !== 'user') return;
                var html = q.root.innerHTML;
                input.value = (q.getText().trim() === '' && html.indexOf('<img') === -1) ? '' : html;
                markDirty();
            });
        });
    }
    initEditors(document);

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-act]');
        if (!btn) return;
        var act = btn.dataset.act;
        var row = btn.closest('[data-row]');

        if (act === 'add-row') {
            var rep = btn.closest('[data-repeater]');
            var tpl = rep.querySelector(':scope > template');
            var n = parseInt(rep.dataset.next || '0', 10);
            rep.dataset.next = n + 1;
            var wrap = document.createElement('div');
            wrap.innerHTML = tpl.innerHTML.split(rep.dataset.ph).join(n);
            // make element ids unique too (ids contain md5 of the name, already unique per index)
            var node = wrap.firstElementChild;
            uniquify(node);
            rep.querySelector(':scope > .cms-rows').appendChild(node);
            initEditors(node);
            markDirty();
            var first = node.querySelector('input[type=text],textarea');
            if (first) first.focus();
        } else if (act === 'remove' && row) {
            if (confirm('Remove this item?')) { row.remove(); markDirty(); }
        } else if (act === 'up' && row && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
            markDirty(); btn.focus();
        } else if (act === 'down' && row && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
            markDirty(); btn.focus();
        } else if (act === 'clear-file') {
            markDirty();
            var box = btn.closest('.cms-upload');
            box.querySelector('.cms-path').value = '';
            var prev = box.querySelector('.cms-preview'); if (prev) prev.classList.add('hidden');
            var f = box.querySelector('input[type=file]'); if (f) f.value = '';
        }
    });

    // live row titles
    document.addEventListener('input', function (e) {
        var t = e.target; if (!t.name) return;
        var row = t.closest('[data-row]'); if (!row) return;
        var title = row.querySelector(':scope > div .cms-row-title');
        if (title && t.name.slice(-(title.dataset.labelKey.length + 2)) === '[' + title.dataset.labelKey + ']') {
            title.textContent = t.value.trim().slice(0, 80) || 'New item';
        }
    });

    // dim the section body when toggled off
    document.querySelectorAll('input[type=checkbox][name^="vis["]').forEach(function (cb) {
        cb.addEventListener('change', function () { cb.closest('section').classList.toggle('cms-section-hidden', !cb.checked); });
    });

    document.querySelectorAll('[data-confirm]').forEach(function (b) {
        b.addEventListener('click', function (e) { if (!confirm(b.dataset.confirm)) e.preventDefault(); });
    });
})();
</script>
@endsection
