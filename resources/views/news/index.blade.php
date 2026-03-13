@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'News',
    'subtitle' => 'Latest news, events, and announcements from ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'News', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$articles = [
    [
        'title'    => '13th ASEANAPOL Training Cooperation Meeting (ATCM)',
        'date'     => 'Jul 04, 2025',
        'category' => 'Training',
        'icon'     => 'school',
        'excerpt'  => 'The 13th ASEANAPOL Training Cooperation Meeting (ATCM) was successfully convened, bringing together training representatives from all ten ASEAN member police forces to coordinate and align training programmes across the region.',
    ],
    [
        'title'    => 'ASEANAPOL and Freeland: TRIPOD II — Special Investigation Group (SIG) in Kuala Lumpur',
        'date'     => 'Jul 04, 2025',
        'category' => 'Partnership',
        'icon'     => 'handshake',
        'excerpt'  => 'ASEANAPOL and the Freeland Foundation conducted the TRIPOD II Special Investigation Group (SIG) meeting in Kuala Lumpur, strengthening joint efforts to combat wildlife trafficking and transnational organised crime.',
    ],
    [
        'title'    => 'ASEANAPOL and Freeland: TRIPOD II — Counter Transnational Organised Crime – Wildlife Trafficking (CTOC-W1) in Hai Phong, Vietnam',
        'date'     => 'Jun 29, 2025',
        'category' => 'Operations',
        'icon'     => 'forest',
        'excerpt'  => 'A joint ASEANAPOL–Freeland TRIPOD II counter-trafficking operation targeting wildlife crime was conducted in Hai Phong, Vietnam, as part of the ongoing CTOC-W1 initiative.',
    ],
    [
        'title'    => 'ASEANAPOL Secretariat Strengthens Regional Synergy at the 25th SOMTC, Putrajaya',
        'date'     => 'Jun 29, 2025',
        'category' => 'Diplomacy',
        'icon'     => 'forum',
        'excerpt'  => 'The ASEANAPOL Secretariat participated in the 25th ASEAN Senior Officials Meeting on Transnational Crime (SOMTC) held in Putrajaya, Malaysia, reinforcing regional cooperation frameworks.',
    ],
    [
        'title'    => 'ASEANAPOL Executive Director Attends RMP IGP Handover and Retirement Ceremony',
        'date'     => 'Jun 27, 2025',
        'category' => 'Secretariat',
        'icon'     => 'military_tech',
        'excerpt'  => 'The ASEANAPOL Executive Director attended the handover ceremony marking the retirement of the Royal Malaysia Police Inspector-General of Police, underscoring the close ties between ASEANAPOL and its host country.',
    ],
    [
        'title'    => 'Strengthening the Heart Behind the Badge: ASEANAPOL and IACP-IMPACT Section Webinar',
        'date'     => 'Jun 27, 2025',
        'category' => 'Training',
        'icon'     => 'people',
        'excerpt'  => 'ASEANAPOL and the International Association of Chiefs of Police (IACP) IMPACT Section co-hosted a webinar focused on officer wellness, mental health, and building resilient police institutions.',
    ],
    [
        'title'    => 'ASEANAPOL Reaffirms Commitment to ASEAN Vision 2045 at the 9th Forum of Entities',
        'date'     => 'Jun 27, 2025',
        'category' => 'Diplomacy',
        'icon'     => 'public',
        'excerpt'  => 'ASEANAPOL participated in the 9th Forum of Entities, reaffirming its commitment to the ASEAN Vision 2045 and its role in contributing to a peaceful, safe, and secure ASEAN community.',
    ],
];

$categoryColors = [
    'Training'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'Partnership'=> 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'Operations' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    'Diplomacy'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'Secretariat'=> 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filter bar (static display) --}}
        <div class="flex flex-wrap items-center gap-3 mb-10">
            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Filter:</span>
            @php $cats = ['All', 'Training', 'Partnership', 'Operations', 'Diplomacy', 'Secretariat']; @endphp
            @foreach($cats as $i => $cat)
            <button class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $i === 0 ? 'bg-primary text-white' : 'bg-white dark:bg-dark-card text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:border-primary/30' }}">
                {{ $cat }}
            </button>
            @endforeach
        </div>

        {{-- Articles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($articles as $article)
            <article class="card-hover bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                {{-- Placeholder image header --}}
                <div class="h-40 bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white/40 text-6xl">{{ $article['icon'] }}</span>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $categoryColors[$article['category']] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $article['category'] }}
                        </span>
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            {{ $article['date'] }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug mb-3 flex-1">{{ $article['title'] }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-4">{{ $article['excerpt'] }}</p>
                    <a href="#" class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline mt-auto">
                        Read More <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
