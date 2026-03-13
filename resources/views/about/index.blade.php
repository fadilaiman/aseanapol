@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'About ASEANAPOL',
    'subtitle' => 'The ASEAN Chiefs of Police — promoting police professionalism and regional cooperation since 1981.',
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$subpages = [
    [
        'title' => 'Permanent Secretariat',
        'icon'  => 'domain',
        'route' => 'about.permanent-secretariat',
        'desc'  => 'Learn about the ASEANAPOL Secretariat based in Kuala Lumpur, Malaysia.',
    ],
    [
        'title' => 'Governance',
        'icon'  => 'account_balance',
        'route' => 'about.governance',
        'desc'  => 'ASEANAPOL\'s governance structure — the Conference, Executive Committee, and Permanent Secretariat.',
    ],
    [
        'title' => 'Objectives and Functions',
        'icon'  => 'flag',
        'route' => 'about.objectives-and-functions',
        'desc'  => 'The core objectives and functions that guide ASEANAPOL\'s work across the region.',
    ],
    [
        'title' => 'Chronology',
        'icon'  => 'history',
        'route' => 'about.chronology',
        'desc'  => 'A timeline of ASEANAPOL\'s history from its founding in 1981 to the present day.',
    ],
    [
        'title' => 'ASEANAPOL Logo',
        'icon'  => 'stars',
        'route' => 'about.logo',
        'desc'  => 'The symbolism and design elements behind the official ASEANAPOL logo.',
    ],
    [
        'title' => 'Vision and Mission',
        'icon'  => 'visibility',
        'route' => 'about.vision-mission',
        'desc'  => 'ASEANAPOL\'s vision as the premier regional police organisation and our guiding mission.',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Intro --}}
        <div class="max-w-3xl mb-12">
            <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                ASEANAPOL (Association of Southeast Asian Nations Chiefs of Police) is a regional police organisation established in 1981. It serves as the primary platform for police cooperation among the ten ASEAN member states, working to combat transnational crime and promote public safety across the region.
            </p>
        </div>

        {{-- Subpage Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($subpages as $page)
            <a href="{{ route($page['route'], ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-white dark:bg-dark-card border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/5 dark:bg-primary/20 flex items-center justify-center group-hover:bg-primary dark:group-hover:bg-accent transition-all">
                        <span class="material-symbols-outlined text-primary dark:text-accent group-hover:text-white dark:group-hover:text-primary text-2xl transition-colors">{{ $page['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ $page['title'] }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $page['desc'] }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4 text-primary dark:text-accent text-sm font-medium gap-1">
                    Learn more <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
