@php
    $__cur = isset($news) ? ($news->sponsors ?? []) : [];
    $__label = old('sponsors_label', $__cur['label'] ?? '');
    if (old('sponsors') !== null || old('sponsors_label') !== null) {
        $__rows = collect((array) old('sponsors', []))->map(fn ($r) => [
            'name' => $r['name'] ?? '', 'url' => $r['url'] ?? '',
            'logo' => ($r['logo_existing'] ?? '') ?: ($r['logo_url'] ?? ''),
        ])->values()->all();
    } else {
        $__rows = collect($__cur['items'] ?? [])->map(fn ($r) => [
            'name' => $r['name'] ?? '', 'url' => $r['url'] ?? '', 'logo' => $r['logo'] ?? '',
        ])->values()->all();
    }
    $__inp = 'w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300';
@endphp

<fieldset class="border border-gray-200 rounded-lg p-4" data-testid="sponsors-block">
    <legend class="px-2 text-sm font-medium text-gray-700">Supported by / sponsors</legend>
    <p class="text-xs text-gray-400 mb-3">Optional. Logos show beside "Read More" on the news list and in a "Supported by" strip on the article page. Max 8.</p>

    <div class="mb-4">
        <label class="block text-xs font-medium text-gray-600 mb-1">Label <span class="text-gray-400 font-normal">(optional, e.g. "Supported by Japan-ASEAN Integration Fund (JAIF)"; blank uses the site default)</span></label>
        <input type="text" name="sponsors_label" value="{{ $__label }}" maxlength="160" data-testid="sponsors-label" class="{{ $__inp }}">
    </div>

    <div id="sponsor-rows" class="space-y-3" data-testid="sponsor-rows">
        @foreach($__rows as $i => $r)
            @include('admin.news._sponsor-row', ['key' => 'o'.$i, 'r' => $r, 'inp' => $__inp])
        @endforeach
    </div>

    <button type="button" id="sponsor-add" data-testid="sponsor-add"
            class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span> Add sponsor
    </button>

    <template id="sponsor-tpl">
        @include('admin.news._sponsor-row', ['key' => '__KEY__', 'r' => ['name' => '', 'url' => '', 'logo' => ''], 'inp' => $__inp])
    </template>
</fieldset>

<script>
(function () {
    var wrap = document.getElementById('sponsor-rows');
    var tpl = document.getElementById('sponsor-tpl');
    var MAX = 8, seq = 0;
    function count() { return wrap.querySelectorAll('[data-sponsor-row]').length; }
    function sync() { document.getElementById('sponsor-add').disabled = count() >= MAX; }
    document.getElementById('sponsor-add').addEventListener('click', function () {
        if (count() >= MAX) return;
        var html = tpl.innerHTML.split('__KEY__').join('n' + (++seq) + Date.now().toString(36));
        var d = document.createElement('div'); d.innerHTML = html.trim();
        wrap.appendChild(d.firstElementChild); sync();
    });
    wrap.addEventListener('click', function (e) {
        var b = e.target.closest('button[data-act]'); if (!b) return;
        var row = b.closest('[data-sponsor-row]');
        var act = b.getAttribute('data-act');
        if (act === 'remove') row.remove();
        else if (act === 'up' && row.previousElementSibling) wrap.insertBefore(row, row.previousElementSibling);
        else if (act === 'down' && row.nextElementSibling) wrap.insertBefore(row.nextElementSibling, row);
        sync();
    });
    sync();
})();
</script>
