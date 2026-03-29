@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Joint Projects & Operations',
    'subtitle' => 'ASEANAPOL's active participation in joint law enforcement operations and multilateral programmes targeting transnational crime.',
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => 'International Cooperation', 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => 'Joint Projects',            'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Intro --}}
        <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
            ASEANAPOL actively participates in joint law enforcement operations and multilateral programmes led by international
            partner organisations. These initiatives range from counter-terrorism and cybercrime operations to wildlife
            crime and pharmaceutical fraud enforcement.
        </p>

        {{-- Operations grid --}}
        @php
        $projects = [
            [
                'name'    => 'CT-SEA / Operation Maharlika',
                'partner' => 'INTERPOL',
                'icon'    => 'security',
                'type'    => 'Counter-Terrorism',
                'status'  => 'Active',
                'desc'    => 'INTERPOL\'s Counter-Terrorism Southeast Asia (CT-SEA) project involves coordinated operations targeting terrorism financing and foreign terrorist fighter networks. ASEANAPOL member countries have participated in multiple Maharlika deployments, including pre-deployment training in Hanoi, Vietnam (2024–2025).',
            ],
            [
                'name'    => 'Operation Pangea',
                'partner' => 'INTERPOL',
                'icon'    => 'medication',
                'type'    => 'Health Crime',
                'status'  => 'Active',
                'desc'    => 'A global operation targeting online trafficking of counterfeit and substandard medicines. ASEANAPOL participates in annual editions coordinated through INTERPOL\'s Health Crime sub-directorate, supporting Southeast Asian law enforcement agencies in joint enforcement actions.',
            ],
            [
                'name'    => 'Project Torii',
                'partner' => 'INTERPOL',
                'icon'    => 'manage_search',
                'type'    => 'Organised Crime',
                'status'  => 'Active',
                'desc'    => 'An INTERPOL-coordinated initiative targeting criminal networks involved in fraud and other serious financial crimes in the Asia Pacific region. ASEANAPOL facilitates information-sharing among member country units engaged with this project.',
            ],
            [
                'name'    => 'ASPJOC',
                'partner' => 'INTERPOL',
                'icon'    => 'wifi_protected_setup',
                'type'    => 'Cybercrime',
                'status'  => 'Active',
                'desc'    => 'Asia and South Pacific Joint Operations Against Cybercrime. ASEANAPOL participated in the 2024 ASPJOC operation in Manila, supporting member country law enforcement agencies in coordinated action against cybercrime networks.',
            ],
            [
                'name'    => 'I-GAIN',
                'partner' => 'INTERPOL',
                'icon'    => 'hub',
                'type'    => 'Criminal Intelligence',
                'status'  => 'Active',
                'desc'    => 'INTERPOL\'s Global Analysis and Intelligence Network. ASEANAPOL joined the inaugural I-GAIN meeting in 2024, contributing regional criminal intelligence analysis to the global network.',
            ],
            [
                'name'    => 'SEAJust',
                'partner' => 'UNODC',
                'icon'    => 'gavel',
                'type'    => 'Justice Cooperation',
                'status'  => 'Active',
                'desc'    => 'The South East Asia Justice Network, led by UNODC, strengthens cross-border judicial and prosecutorial cooperation. ASEANAPOL contributes the law enforcement perspective to this justice chain initiative.',
            ],
            [
                'name'    => 'TRIPOD II',
                'partner' => 'Freeland Foundation',
                'icon'    => 'forest',
                'type'    => 'Wildlife & Human Trafficking',
                'status'  => 'Past',
                'desc'    => 'A joint programme between ASEANAPOL and the Freeland Foundation targeting both wildlife trafficking and human trafficking networks in Southeast Asia, combining intelligence sharing with coordinated enforcement operations.',
            ],
            [
                'name'    => 'e-ADS Technical Development',
                'partner' => 'INTERPOL (IASIWG)',
                'icon'    => 'database',
                'type'    => 'Information Systems',
                'status'  => 'Ongoing',
                'desc'    => 'The INTERPOL–ASEANAPOL System Integration Working Group (IASIWG) oversees the continuous technical development of the e-ADS on INTERPOL\'s I-24/7 network, ensuring the system meets evolving operational and security requirements.',
            ],
        ];
        @endphp

        <div class="space-y-5">
            @foreach($projects as $p)
            @php $statusCls = match($p['status']) { 'Active', 'Ongoing' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }; @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $p['icon'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $p['name'] }}</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusCls }}">{{ $p['status'] }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">{{ $p['type'] }}</span>
                    </div>
                    <p class="text-xs text-primary dark:text-accent font-medium mb-2">Partner: {{ $p['partner'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endsection
