@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Training Programs',
    'subtitle' => 'Capacity-building courses, joint exercises, and specialised training offered to ASEANAPOL member police forces.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                    ['locale' => app()->getLocale()])],
        ['label' => 'Events & Training','url' => route('events-training.index',      ['locale' => app()->getLocale()])],
        ['label' => 'Training Programs','url' => ''],
    ],
])
@endsection

@section('content')
@php
$programs = [
    [
        'title'    => 'ASEANAPOL Training Cooperation Meeting (ATCM)',
        'abbr'     => 'ATCM',
        'icon'     => 'groups',
        'category' => 'Governance',
        'freq'     => 'Annual',
        'desc'     => 'The principal forum for ASEANAPOL member police forces to plan, review, and coordinate training cooperation activities. The 13th ATCM was held in Yangon, Myanmar in July 2025, adopting a hybrid format for the first time.',
        'latest'   => '13th ATCM — July 2025, Yangon, Myanmar',
    ],
    [
        'title'    => 'Cybercrime Investigation Training',
        'abbr'     => 'CIT',
        'icon'     => 'security',
        'category' => 'Cybercrime',
        'freq'     => 'Bi-annual',
        'desc'     => 'Specialised training covering digital forensics, ransomware investigation, online fraud detection, and cyber-enabled financial crime. Delivered in partnership with INTERPOL and Europol.',
        'latest'   => 'Delivered in 2024–2025 series',
    ],
    [
        'title'    => 'Counter-Trafficking Training Programme',
        'abbr'     => 'CTTP',
        'icon'     => 'person_search',
        'category' => 'TIP',
        'freq'     => 'Annual',
        'desc'     => 'Training on victim identification, cross-border investigation techniques, and cooperation with UNODC and NGO partners for combating trafficking in persons (TIP) across the ASEAN region.',
        'latest'   => 'Held in partnership with UNODC',
    ],
    [
        'title'    => 'Drug Enforcement Cooperation Training',
        'abbr'     => 'DECT',
        'icon'     => 'science',
        'category' => 'Drugs',
        'freq'     => 'Annual',
        'desc'     => 'Joint training for narcotics enforcement officers covering synthetic drug trends, precursor chemical controls, and cross-border drug trafficking investigation methodologies.',
        'latest'   => 'Coordinated with UNODC Southeast Asia',
    ],
    [
        'title'    => 'Financial Crime & Money Laundering Training',
        'abbr'     => 'FCMLT',
        'icon'     => 'account_balance',
        'category' => 'Financial Crime',
        'freq'     => 'Annual',
        'desc'     => 'Capacity building for financial investigators covering asset tracing, trade-based money laundering, and cooperation with Financial Intelligence Units (FIUs) across member states.',
        'latest'   => 'Included in ATCM programme',
    ],
    [
        'title'    => 'Wildlife & Environmental Crime Training',
        'abbr'     => 'WECT',
        'icon'     => 'forest',
        'category' => 'Wildlife',
        'freq'     => 'Bi-annual',
        'desc'     => 'Training on wildlife trafficking investigation, CITES compliance, and coordinated responses to illegal logging and illegal wildlife trade in the ASEAN region.',
        'latest'   => 'In partnership with INTERPOL Environmental Security',
    ],
];

$categoryColors = [
    'Governance'     => 'bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent',
    'Cybercrime'     => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
    'TIP'            => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300',
    'Drugs'          => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
    'Financial Crime'=> 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
    'Wildlife'       => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Intro note --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">school</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                ASEANAPOL training programs are open to police officers from all ten member countries. Training schedules are confirmed at the annual ATCM and published in the
                <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}" class="text-primary dark:text-accent font-semibold hover:underline">Event Calendar</a>.
                For nominations and enquiries, contact your national ASEANAPOL focal point or the Secretariat at
                <a href="mailto:info@aseanapol.org" class="text-primary dark:text-accent font-semibold hover:underline">info@aseanapol.org</a>.
            </p>
        </div>

        {{-- Program list --}}
        <div class="space-y-5">
            @foreach($programs as $p)
            <article class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row gap-5">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $p['icon'] }}</span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 dark:text-white leading-snug">{{ $p['title'] }}</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $categoryColors[$p['category']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }} uppercase tracking-wider">{{ $p['category'] }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3">{{ $p['desc'] }}</p>
                        <div class="flex flex-wrap gap-4 text-xs text-gray-400 dark:text-gray-500">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">repeat</span>
                                {{ $p['freq'] }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">event</span>
                                {{ $p['latest'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Calendar CTA --}}
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                See the full schedule of upcoming training sessions and events.
            </p>
            <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                View Event Calendar
            </a>
        </div>

    </div>
</section>
@endsection
