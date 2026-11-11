@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
@php
    $tiles = [
        ['route' => 'admin.news.index',                  'icon' => 'newspaper',     'color' => 'text-rose-600',    'label' => 'News articles',  'count' => $newsCount,        'sub' => $latestNews && $latestNews->published_at ? 'Latest: ' . $latestNews->published_at->format('d M Y') : 'No articles yet', 'subColor' => 'text-gray-500'],
        ['route' => 'admin.events.index',                'icon' => 'event',         'color' => 'text-sky-600',     'label' => 'Events',         'count' => $eventCount,       'sub' => $publishedEventCount . ' visible',       'subColor' => 'text-green-600'],
        ['route' => 'admin.publications.index',          'icon' => 'menu_book',     'color' => 'text-orange-600',  'label' => 'Publications',   'count' => $publicationCount, 'sub' => $publishedPublicationCount . ' published', 'subColor' => 'text-green-600'],
        ['route' => 'admin.digital-library.items.index', 'icon' => 'library_books', 'color' => 'text-teal-600',    'label' => 'Library items',  'count' => $libraryCount,     'sub' => $publishedLibraryCount . ' published',     'subColor' => 'text-green-600'],
        ['route' => 'admin.video-gallery.index',         'icon' => 'play_circle',   'color' => 'text-blue-600',    'label' => 'Videos',         'count' => $videoCount,       'sub' => $publishedVideoCount . ' published',       'subColor' => 'text-green-600'],
        ['route' => 'admin.newsletters.index',           'icon' => 'mail',          'color' => 'text-indigo-600',  'label' => 'Newsletters',    'count' => $newsletterCount,  'sub' => $publishedNewsletterCount . ' published',  'subColor' => 'text-green-600'],
        ['route' => 'admin.cms.index',                   'icon' => 'account_tree',  'color' => 'text-emerald-600', 'label' => 'Editable Pages', 'count' => $cmsPageCount,     'sub' => $cmsCustomisedPages . ' customised',     'subColor' => 'text-amber-600', 'testid' => 'dash-cms-tile'],
    ];
    $actions = [
        ['route' => 'admin.news.create',                  'color' => 'rose',    'title' => 'Add Article',     'desc' => 'Publish a news article with photos'],
        ['route' => 'admin.events.create',                'color' => 'sky',     'title' => 'Add Event',       'desc' => 'Add to the calendar, conferences or training'],
        ['route' => 'admin.publications.create',          'color' => 'orange',  'title' => 'Add Publication', 'desc' => 'Add a bulletin or magazine edition'],
        ['route' => 'admin.digital-library.items.create', 'color' => 'teal',    'title' => 'Add Library Item', 'desc' => 'Add a PDF or web link to the Digital Library'],
        ['route' => 'admin.video-gallery.create',         'color' => 'blue',    'title' => 'Add Video',       'desc' => 'Add a new video to the gallery'],
        ['route' => 'admin.newsletters.create',           'color' => 'indigo',  'title' => 'Add Newsletter',  'desc' => 'Add a new newsletter edition'],
        ['route' => 'admin.cms.index',                    'color' => 'emerald', 'title' => 'Edit Site Pages', 'desc' => "Browse the page tree and edit any page's content", 'icon' => 'edit_note'],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    @foreach($tiles as $t)
        <a href="{{ route($t['route']) }}" @if(!empty($t['testid'])) data-testid="{{ $t['testid'] }}" @endif
           class="block bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-sm transition-all">
            <div class="flex items-center gap-2 {{ $t['color'] }} mb-3">
                <span class="material-symbols-outlined" style="font-size:22px;">{{ $t['icon'] }}</span>
                <span class="text-sm text-gray-500 font-medium">{{ $t['label'] }}</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($t['count']) }}</div>
            <div class="text-xs {{ $t['subColor'] }} mt-1">{{ $t['sub'] }}</div>
        </a>
    @endforeach
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    @foreach($actions as $a)
        <a href="{{ route($a['route']) }}"
           class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4 hover:border-{{ $a['color'] }}-300 hover:shadow-sm transition-all group">
            <span class="material-symbols-outlined text-{{ $a['color'] }}-500 group-hover:text-{{ $a['color'] }}-600" style="font-size:24px;">{{ $a['icon'] ?? 'add_circle' }}</span>
            <div>
                <div class="font-semibold text-gray-800 text-sm">{{ $a['title'] }}</div>
                <div class="text-gray-400 text-xs">{{ $a['desc'] }}</div>
            </div>
        </a>
    @endforeach
</div>
@endsection
