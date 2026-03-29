@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'News & Media',
    'subtitle' => 'The latest news, press releases, and multimedia resources from ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',         ['locale' => app()->getLocale()])],
        ['label' => 'News & Media','url' => ''],
    ],
])
@endsection

@section('content')
@php
$sections = [
    [
        'icon'   => 'newspaper',
        'title'  => 'News',
        'desc'   => 'Latest news articles, announcements, and updates from ASEANAPOL and its member police forces.',
        'route'  => 'news.index',
        'label'  => 'Browse Articles',
        'status' => 'active',
    ],
    [
        'icon'   => 'campaign',
        'title'  => 'Press Releases',
        'desc'   => 'Official press statements and media releases issued by the ASEANAPOL Permanent Secretariat.',
        'route'  => 'news-media.press-releases',
        'label'  => 'Read Releases',
        'status' => 'active',
    ],
    [
        'icon'   => 'photo_library',
        'title'  => 'Photo Gallery',
        'desc'   => 'A visual archive of ASEANAPOL events, activities, governance, and partner engagements.',
        'route'  => 'news-media.gallery',
        'label'  => 'Browse Gallery',
        'status' => 'active',
    ],
    [
        'icon'   => 'video_library',
        'title'  => 'Video Gallery',
        'desc'   => 'Video recordings of ASEANAPOL events, ceremonies, and training activities.',
        'route'  => 'news-media.video-gallery',
        'label'  => 'Watch Videos',
        'status' => 'active',
    ],
    [
        'icon'   => 'mail',
        'title'  => 'Newsletters',
        'desc'   => 'Periodic newsletters distributed to ASEANAPOL member police forces and stakeholders.',
        'route'  => 'news-media.newsletters',
        'label'  => 'Browse Newsletters',
        'status' => 'active',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sections as $s)
            <a href="{{ route($s['route'], ['locale' => app()->getLocale()]) }}"
               class="group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-4
                      {{ $s['status'] === 'active' ? 'hover:shadow-md hover:border-primary/30 dark:hover:border-primary/40' : 'opacity-70 cursor-default pointer-events-none' }} transition-all">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl {{ $s['status'] === 'active' ? 'bg-primary/8 dark:bg-primary/20' : 'bg-gray-100 dark:bg-gray-700/50' }} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ $s['status'] === 'active' ? 'text-primary dark:text-accent' : 'text-gray-400 dark:text-gray-500' }} text-2xl">{{ $s['icon'] }}</span>
                    </div>
                    @if($s['status'] === 'soon')
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 uppercase tracking-wider">Coming Soon</span>
                    @else
                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-primary dark:group-hover:text-accent transition-colors">arrow_forward</span>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-bold {{ $s['status'] === 'active' ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                <div class="text-xs font-semibold {{ $s['status'] === 'active' ? 'text-primary dark:text-accent group-hover:underline' : 'text-gray-400' }}">
                    {{ $s['label'] }} @if($s['status'] === 'active')→@endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
