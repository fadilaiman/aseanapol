@extends('admin.layout')
@section('title', $def['label'] . ' — Page Content')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" integrity="sha384-ecIckRi4QlKYya/FQUbBUjS4qp65jF/J87Guw5uzTbO1C1Jfa/6kYmd6dXUF6D7i" crossorigin="anonymous">
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

<div data-testid="translation-note" class="flex items-start gap-2 bg-blue-50 border border-blue-100 text-blue-900 text-xs px-4 py-2.5 rounded-lg mb-5">
    <span class="material-symbols-outlined" style="font-size:16px;">translate</span>
    <span><strong>Languages:</strong> text you change here is saved in English. On the other language versions it is machine-translated in the visitor's browser, like the rest of the page; no ready-made translation is stored for edited text. Only the original, unedited wording keeps its prepared translation. (News articles are different: saving a news article re-generates its stored translations automatically.)</span>
</div>

@if($errors->any())
    <div role="alert" data-testid="cms-error" class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">{{ $errors->first() }}</div>
@endif
@if(!empty($uploadError))
    <div role="alert" data-testid="cms-error" class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">
        <strong>Not saved.</strong> {{ $uploadError }} Your other edits are still shown below; files you selected must be chosen again.
    </div>
@endif
@if(!empty($conflict))
    <div role="alert" data-testid="cms-conflict" class="bg-amber-50 border border-amber-300 text-amber-900 text-sm px-4 py-3 rounded-lg mb-5">
        <strong>Not saved &mdash; this page was changed by someone else</strong> after you opened it.
        Your edits are shown below but have NOT been saved yet. Press <em>Save changes</em> to overwrite their version with yours,
        or <a class="underline" href="{{ route('admin.cms.edit', $pageKey) }}">reload</a> to see their changes (your edits will be lost).
        Files you selected for upload must be chosen again.
    </div>
@endif

@if(!empty($feeds))
<div data-testid="feeds" class="bg-blue-50 border border-blue-200 text-blue-900 text-sm px-4 py-3 rounded-lg mb-5">
    <strong>Feeds these public pages:</strong>
    <ul class="mt-1 flex flex-wrap gap-x-5 gap-y-1">
        @foreach($feeds as $fd)
            <li><a href="{{ $fd['url'] }}" target="_blank" rel="noopener" class="underline hover:text-blue-700">{{ $fd['label'] }}</a></li>
        @endforeach
    </ul>
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

<form id="cms-form" method="POST" action="{{ route('admin.cms.update', $pageKey) }}" enctype="multipart/form-data" class="space-y-5 pb-40">
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
                    @foreach(\App\Support\Cms::seeLinks($section) as $seeKey => $seeLabel)
                        <p class="text-xs mt-1"><a href="{{ route('admin.cms.edit', $seeKey) }}" data-testid="see-{{ $seeKey }}" class="inline-flex items-center gap-1 font-semibold text-blue-700 hover:underline"><span class="material-symbols-outlined" style="font-size:14px;">edit_note</span>{{ $seeLabel }}</a></p>
                    @endforeach
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
            @if(!empty($section['roster_post']))
            @php
                $rosterLabels = ['ed' => 'Executive Director', 'dps' => 'Director for Police Services', 'dpp' => 'Director for Plans and Programmes'];
                $rosterUrl = route('admin.cms.edit', 'shared.roster');
            @endphp
            <div class="basis-full space-y-3" data-testid="roster-panel-{{ $sKey }}">
                @foreach((array) $section['roster_post'] as $rp)
                    @php $people = \App\Support\Shared::roster($rp, null, true); @endphp
                    <div class="rounded-lg border border-blue-200 bg-blue-50/60 p-3">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <p class="text-xs font-semibold text-blue-900">People shown for {{ $rosterLabels[$rp] ?? $rp }} <span class="font-normal text-blue-700">(read-only, as saved; newest first)</span></p>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ $rosterUrl }}#post={{ $rp }}" data-testid="roster-edit-{{ $rp }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white" style="background:#0a2540;"><span class="material-symbols-outlined" style="font-size:16px;">edit_note</span>Add / edit people for {{ $rosterLabels[$rp] ?? $rp }}</a>
                                <a href="{{ $rosterUrl }}#add={{ $rp }}" data-testid="roster-add-{{ $rp }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-400 text-blue-800 bg-white"><span class="material-symbols-outlined" style="font-size:16px;">person_add</span>Add new {{ $rosterLabels[$rp] ?? $rp }}</a>
                            </div>
                        </div>
                        @if($people)
                        <ul class="text-xs text-gray-700 divide-y divide-blue-100 bg-white rounded border border-blue-100">
                            @foreach($people as $pp)
                                <li class="flex items-center gap-2 px-2.5 py-1.5">
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $pp['is_current'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">{{ $pp['is_current'] ? 'Current' : 'Past' }}</span>
                                    <span class="font-medium">{{ $pp['full_name'] }}</span>
                                    <span class="text-gray-400">{{ $pp['force'] }}</span>
                                    <span class="ml-auto text-gray-500 whitespace-nowrap">{{ $pp['term'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @else
                            <p class="text-xs text-gray-500">No visible people for this post yet.</p>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif
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

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js" integrity="sha384-utBUCeG4SYaCm4m7GQZYr8Hy8Fpy3V4KGjBZaf4WTKOcwhCYpt/0PfeEe3HNlwx8" crossorigin="anonymous"></script>
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
        if (title && title.dataset.tpl) {
            refreshRow(row);
        } else if (title && t.name.slice(-(title.dataset.labelKey.length + 2)) === '[' + title.dataset.labelKey + ']') {
            title.textContent = t.value.trim().slice(0, 80) || 'New item';
        }
        var rep = row.closest('[data-repeater]');
        if (rep && rep.dataset.badge) refreshBadges(rep);
    });
    document.addEventListener('change', function (e) {
        var row = e.target.closest && e.target.closest('[data-row]'); if (!row) return;
        var rep = row.closest('[data-repeater]'); if (!rep) return;
        var title = row.querySelector(':scope > div .cms-row-title');
        if (title && title.dataset.tpl) refreshRow(row);
        if (rep.dataset.badge) refreshBadges(rep);
        if (rep.dataset.filterBy && e.target.name && e.target.name.slice(-(rep.dataset.filterBy.length + 2)) === '[' + rep.dataset.filterBy + ']') {
            row.dataset.filterVal = e.target.value;
        }
    });

    // --- row helpers (row title template, Current/Past badge, filter) -------------------------------
    function fld(row, key) {
        var els = row.querySelectorAll('input[name$="[' + key + ']"],select[name$="[' + key + ']"],textarea[name$="[' + key + ']"]');
        for (var i = els.length - 1; i >= 0; i--) {
            var el = els[i];
            if (el.closest('[data-row]') !== row) continue;
            if (el.type === 'hidden' && els.length > 1 && i > 0) continue; // checkbox pair: look at the checkbox
            return el;
        }
        return null;
    }
    function fval(row, key) {
        var el = fld(row, key); if (!el) return '';
        if (el.type === 'checkbox') return el.checked ? '1' : '';
        return (el.value || '').trim();
    }
    function shortLabel(row, key, v) {
        var el = fld(row, key);
        if (el && el.tagName === 'SELECT') { var d = el.dataset.short ? JSON.parse(el.dataset.short) : null; if (d && d[v]) return d[v]; var o = el.options[el.selectedIndex]; return o ? o.text : v; }
        return v;
    }
    function refreshRow(row) {
        var title = row.querySelector(':scope > div .cms-row-title');
        if (!title || !title.dataset.tpl) return;
        var out = title.dataset.tpl.replace(/\{(\w+)(?::(short))?(?:\|([^}]*))?\}/g, function (m, f, sh, alt) {
            var v = fval(row, f);
            if (f === 'term_end' && v === '' && row.dataset.dispEnd) v = row.dataset.dispEnd;
            if (sh) v = shortLabel(row, f, v);
            if (v === '' && alt !== undefined) v = fld(row, alt) ? fval(row, alt) : alt;
            return v;
        }).trim();
        title.textContent = (out.replace(/[\s\u2014\u2013()\-]/g, '') === '' ? 'New item' : out).slice(0, 80);
    }
    function year(v) { var n = parseInt(String(v).replace(/\D.*$/, ''), 10); return isNaN(n) ? 0 : n; }
    // mirrors App\Support\Shared::currentFlags()
    function refreshBadges(rep) {
        var rows = Array.prototype.filter.call(rep.querySelectorAll(':scope > .cms-rows > [data-row]'), function (r) {
            var h = r.querySelector('input[name$="[_hide]"]'); return !(h && h.checked);
        });
        var byPost = {};
        rows.forEach(function (r) { (byPost[fval(r, 'post')] = byPost[fval(r, 'post')] || []).push(r); });
        var win = [];
        Object.keys(byPost).forEach(function (k) {
            var idx = byPost[k];
            var forced = idx.filter(function (r) { return fval(r, 'is_current') === '1'; });
            var open = idx.filter(function (r) { return fval(r, 'term_end') === ''; });
            var pool = forced.length ? forced : (open.length ? open : idx), w = null;
            pool.forEach(function (r) { if (w === null || year(fval(r, 'term_start')) >= year(fval(w, 'term_start'))) w = r; });
            if (w) win.push(w);
        });
        // superseded holders with an empty term end: end = start of the next newer holder of the post (display only)
        Object.keys(byPost).forEach(function (k) {
            var list = byPost[k].map(function (r, n) { return { r: r, n: n, y: year(fval(r, 'term_start')) }; })
                .sort(function (a, b) { return a.y - b.y || a.n - b.n; });
            list.forEach(function (it, j) {
                var nx = list[j + 1], ny = nx ? String(fval(nx.r, 'term_start')).replace(/\D.*$/, '') : '';
                it.r.dataset.dispEnd = (win.indexOf(it.r) === -1 && fval(it.r, 'term_end') === '' && ny) ? ny : '';
            });
        });
        rep.querySelectorAll(':scope > .cms-rows > [data-row]').forEach(refreshRow);
        rep.querySelectorAll(':scope > .cms-rows > [data-row]').forEach(function (r) {
            var b = r.querySelector(':scope > div .cms-badge'); if (!b) return;
            var h = r.querySelector('input[name$="[_hide]"]');
            if (h && h.checked) { b.className = 'cms-badge text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap bg-yellow-100 text-yellow-800'; b.textContent = 'Hidden'; return; }
            var cur = win.indexOf(r) !== -1;
            b.className = 'cms-badge text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap ' + (cur ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600');
            b.textContent = cur ? 'Current' : 'Past';
        });
    }
    function applyFilter(rep, val) {
        rep.dataset.filter = val;
        rep.querySelectorAll(':scope > [data-testid=row-filter] [data-act=filter]').forEach(function (b) {
            var on = b.dataset.val === val;
            b.className = 'px-2.5 py-1 rounded-full border ' + (on ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 text-gray-700 bg-white');
        });
        rep.querySelectorAll(':scope > .cms-rows > [data-row]').forEach(function (r) {
            var v = fld(r, rep.dataset.filterBy); v = v ? v.value : (r.dataset.filterVal || '');
            r.style.display = (val === '' || v === val) ? '' : 'none';
        });
    }
    document.addEventListener('click', function (e) {
        var b = e.target.closest('[data-act=filter]'); if (!b) return;
        applyFilter(b.closest('[data-repeater]'), b.dataset.val);
    });
    document.querySelectorAll('[data-repeater][data-badge]').forEach(refreshBadges);

    // deep links from other editors: #post=dps filters + scrolls to the repeater, #add=dps adds a pre-selected row
    (function () {
        var m = /^#(post|add)=(\w+)$/.exec(location.hash || ''); if (!m) return;
        var rep = document.querySelector('[data-repeater][data-filter-by]'); if (!rep) return;
        if (m[1] === 'add') {
            rep.querySelector(':scope > [data-act=add-row]').click();
            var rows = rep.querySelectorAll(':scope > .cms-rows > [data-row]');
            var nr = rows[rows.length - 1], sel = nr && fld(nr, rep.dataset.filterBy);
            if (sel) { sel.value = m[2]; nr.dataset.filterVal = m[2]; sel.dispatchEvent(new Event('change', { bubbles: true })); }
            applyFilter(rep, m[2]);
            if (nr) { nr.scrollIntoView({ block: 'center' }); }
            try { history.replaceState(null, '', location.pathname + location.search + '#post=' + m[2]); } catch (e) {}
        } else {
            applyFilter(rep, m[2]);
            rep.scrollIntoView();
        }
    })();

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
