@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Data & Resources',
    'subtitle' => 'Publications, statistics, and official documents from ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$sections = [
    [
        'icon'   => 'bar_chart',
        'title'  => 'Crime Statistics',
        'desc'   => 'Public crime data dashboard covering transnational crime trends across ASEAN member states.',
        'route'  => 'data-resources.crime-statistics',
        'label'  => 'View Statistics',
        'status' => 'active',
    ],
    [
        'icon'   => 'menu_book',
        'title'  => 'Publications',
        'desc'   => 'The official ASEANAPOL Bulletin and Magazine series — editions from the 8th (2015) through the 13th (2023).',
        'route'  => 'data-resources.publications',
        'label'  => 'Browse Publications',
        'status' => 'active',
    ],
    [
        'icon'   => 'library_books',
        'title'  => 'Digital Library',
        'desc'   => 'Downloadable guidelines, policy documents, and reference materials from ASEANAPOL.',
        'route'  => 'data-resources.digital-library',
        'label'  => 'Open Library',
        'status' => 'active',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
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
