@extends('admin.layout')
@section('title', 'Site Content — Page Tree')

@section('content')
@php
    $modules = [
        ['route' => 'admin.news.index',                  'label' => 'News & Press',          'icon' => 'newspaper',      'desc' => 'News articles, press items, upcoming-event flags'],
        ['route' => 'admin.newsletters.index',           'label' => 'Newsletters',           'icon' => 'mail',           'desc' => 'Newsletter issues (PDF + topics)'],
        ['route' => 'admin.video-gallery.index',         'label' => 'Video Gallery',         'icon' => 'play_circle',    'desc' => 'Videos shown on the video gallery page'],
        ['route' => 'admin.digital-library.items.index', 'label' => 'Digital Library',       'icon' => 'library_books',  'desc' => 'Library collections and items'],
        ['route' => 'admin.events.index',                'label' => 'Events & Calendar',     'icon' => 'event',          'desc' => 'Calendar events, conferences, training programmes'],
        ['route' => 'admin.publications.index',          'label' => 'Publications',          'icon' => 'menu_book',      'desc' => 'Bulletin & magazine editions'],
    ];
@endphp

<div class="mb-6 flex flex-wrap items-center gap-3">
    <input id="cms-search" type="search" placeholder="Search pages…" data-testid="cms-search"
           class="w-full sm:w-80 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
    <span class="text-sm text-gray-500">Every public page and its editable sections, grouped like the website menu.</span>
</div>

<h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Content modules (lists &amp; records)</h2>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 mb-10">
    @foreach($modules as $m)
        @if(Route::has($m['route']))
        <a href="{{ route($m['route']) }}" class="cms-item flex items-start gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 hover:border-blue-300 hover:shadow-sm" data-name="{{ strtolower($m['label']) }}">
            <span class="material-symbols-outlined text-blue-600" style="font-size:22px;">{{ $m['icon'] }}</span>
            <span><span class="block font-semibold text-sm text-gray-800">{{ $m['label'] }}</span>
            <span class="block text-xs text-gray-400">{{ $m['desc'] }}</span></span>
        </a>
        @endif
    @endforeach
</div>

<h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Page content</h2>
<div class="space-y-6">
@forelse($groups as $gKey => $g)
    <section class="cms-group bg-white rounded-xl border border-gray-200">
        <header class="flex items-center gap-2 px-5 py-3 border-b border-gray-100">
            <span class="material-symbols-outlined text-gray-500" style="font-size:20px;">{{ $g['_group']['icon'] ?? 'article' }}</span>
            <h3 class="font-semibold text-gray-800 text-sm">{{ $g['_group']['label'] }}</h3>
            <span class="text-xs text-gray-400">{{ count($g['pages']) }} page(s)</span>
        </header>
        <ul class="divide-y divide-gray-100">
        @foreach($g['pages'] as $slug => $p)
            @php $key = $gKey.'.'.$slug; $c = $counts[$key] ?? 0; @endphp
            <li class="cms-item flex items-center justify-between gap-3 px-5 py-2.5" data-name="{{ strtolower($p['label'].' '.$g['_group']['label'].' '.$slug) }}">
                <div class="min-w-0">
                    <a href="{{ route('admin.cms.edit', $key) }}" class="text-sm font-medium text-blue-700 hover:underline" data-testid="cms-page-link">{{ $p['label'] }}</a>
                    <div class="text-xs text-gray-400 truncate">{{ collect($p['sections'])->pluck('label')->implode(' · ') }}</div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if($c)<span class="text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2 py-0.5">{{ $c }} customised</span>@endif
                    <a href="{{ route('admin.cms.edit', $key) }}" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Edit</a>
                </div>
            </li>
        @endforeach
        </ul>
    </section>
@empty
    <p class="text-sm text-gray-500">No pages registered yet.</p>
@endforelse
</div>

<script>
document.getElementById('cms-search').addEventListener('input', function () {
    var q = this.value.trim().toLowerCase();
    document.querySelectorAll('.cms-item').forEach(function (el) {
        el.style.display = (!q || (el.dataset.name || '').indexOf(q) !== -1) ? '' : 'none';
    });
    document.querySelectorAll('.cms-group').forEach(function (g) {
        var any = Array.from(g.querySelectorAll('.cms-item')).some(function (i) { return i.style.display !== 'none'; });
        g.style.display = any ? '' : 'none';
    });
});
</script>
@endsection
