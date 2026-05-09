@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Observers',
    'subtitle' => 'Organisations and countries with observer status in ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Information', 'url' => route('information.index', ['locale' => app()->getLocale()])],
        ['label' => 'Observers',   'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                Observer status in ASEANAPOL allows organisations and countries to participate in ASEANAPOL activities and conferences as observers, facilitating broader engagement and information exchange while fostering future cooperative relationships.
            </p>
        </div>

        {{-- Admission Criteria --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8 max-w-3xl">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-4">Observer Admission</h2>
            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                The admission of observers is governed by the Policy Guidelines for Accepting Observers and Dialogue Partners with ASEANAPOL. Interested organisations must meet eligibility criteria and receive approval from the ASEANAPOL Conference.
            </p>
            <a href="{{ route('guidelines.observers-dialogue', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">description</span>
                View Policy Guidelines
            </a>
        </div>

        {{-- Observer list --}}
        @php
        $observers = [
            ['name' => 'Argentine Federal Police (AFP)',                              'flag' => '🇦🇷', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Argentina', 'url' => 'https://www.argentina.gob.ar/seguridad/pfa'],
            ['name' => 'Bangladesh Police',                                           'flag' => '🇧🇩', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Bangladesh', 'url' => 'https://www.police.gov.bd/'],
            ['name' => 'Fiji',                                                        'flag' => '🇫🇯', 'since' => '2016', 'conference' => '36th AC, Malaysia',  'q' => 'Fiji', 'url' => null],
            ['name' => 'French National Police (FNP)',                                'flag' => '🇫🇷', 'since' => '2019', 'conference' => '39th AC, Viet Nam',  'q' => 'French', 'url' => 'https://www.police-nationale.interieur.gouv.fr/'],
            ['name' => 'Security Department of Italian Interior (PSD IIM)',           'flag' => '🇮🇹', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Italian', 'url' => null],
            ['name' => 'Royal Canadian Mounted Police (RCMP)',                        'flag' => '🇨🇦', 'since' => '2019', 'conference' => '39th AC, Viet Nam',  'q' => 'Canada', 'url' => 'https://rcmp.ca/en'],
            ['name' => 'Timor-Leste',                                                 'flag' => '🇹🇱', 'since' => '2014', 'conference' => '34th AC, Philippines','q' => 'Timor', 'url' => null],
            ['name' => 'United Arab Emirates (UAE)',                                  'flag' => '🇦🇪', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'UAE', 'url' => 'https://moi.gov.ae/ar/default.aspx'],
            ['name' => 'International Association of Chiefs of Police (IACP)',        'flag' => null,  'since' => '2016', 'conference' => '36th AC, Malaysia',  'q' => 'IACP', 'url' => 'https://www.theiacp.org/'],
            ['name' => 'International Committee of the Red Cross (ICRC)',             'flag' => null,  'since' => '2015', 'conference' => '35th AC, Indonesia', 'q' => 'ICRC', 'url' => 'https://www.icrc.org/en'],
            ['name' => 'Gulf Cooperation Council Police (GCCPOL)',                   'flag' => null,  'since' => '2019', 'conference' => '39th AC, Viet Nam',  'q' => 'GCCPOL', 'url' => 'https://www.gcc-sg.org/ar/JointGulf/Cooperation/GCCPOL/Pages/default.aspx'],
        ];
        @endphp
        <h2 class="text-xl font-bold text-primary dark:text-white mb-5">Current Observers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            @foreach($observers as $obs)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="w-10 h-10 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    @if($obs['flag'])
                    <span class="text-2xl leading-none">{{ $obs['flag'] }}</span>
                    @else
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">{{ $obs['name'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 mb-2">Since {{ $obs['since'] }} &middot; {{ $obs['conference'] }}</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('news.index', ['locale' => app()->getLocale(), 'q' => $obs['q']]) }}"
                           class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary dark:text-accent hover:underline">
                            <span class="material-symbols-outlined text-sm">open_in_new</span> See Activities
                        </a>
                        @if($obs['url'])
                        <a href="{{ $obs['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-accent hover:underline">
                            <span class="material-symbols-outlined text-sm">language</span> Website
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            <a href="{{ route('information.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to Information
            </a>
        </div>
    </div>
</section>
@endsection
