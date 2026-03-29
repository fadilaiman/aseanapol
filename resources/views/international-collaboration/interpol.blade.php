@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'ASEANAPOL & INTERPOL',
    'subtitle' => 'A strategic partnership strengthening the use of global police intelligence and joint operations to combat transnational crime.',
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => 'International Cooperation', 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => 'INTERPOL',                  'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Overview --}}
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL has maintained a close strategic partnership with <strong>INTERPOL</strong> (International Criminal Police Organization)
                since its establishment. This partnership is most visibly embodied in the <strong>Electronic ASEANAPOL Database System (e-ADS)</strong>,
                which operates on INTERPOL's secure global communication network, I-24/7. ASEANAPOL's Executive Director attends the INTERPOL
                General Assembly annually as part of ongoing engagement.
            </p>
        </div>

        {{-- e-ADS System --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-primary/20 dark:border-primary/30 overflow-hidden">
            <div class="bg-primary px-6 py-4">
                <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined">database</span>
                    Electronic ASEANAPOL Database System (e-ADS)
                </h2>
            </div>
            <div class="p-6 space-y-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                <p>
                    The e-ADS is ASEANAPOL's primary law enforcement database platform, enabling member countries to share
                    criminal intelligence — including wanted persons, missing persons, and stolen property — across the
                    ASEAN region.
                </p>
                <p>
                    The system was first conceived at the 11th ASEANAPOL Conference in 1991 and officially launched in 2006
                    under the leadership of the Singapore Police Force. It operates on INTERPOL's <strong>I-24/7</strong>
                    global communications network, allowing seamless and secure data exchange between ASEANAPOL member
                    countries and INTERPOL's global network.
                </p>
                <p>
                    Ongoing technical development is coordinated through the <strong>ASEANAPOL Database System Technical
                    Committee (ADSTC)</strong>, which meets biennially to review and enhance the system's capabilities,
                    and through bilateral technical meetings with INTERPOL's Information Systems Directorate.
                </p>
            </div>
        </div>

        {{-- Joint Operations --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">Joint Operations & Projects</h2>
            @php
            $ops = [
                ['name' => 'CT-SEA / Operation Maharlika', 'icon' => 'security', 'desc' => 'INTERPOL\'s Counter-Terrorism project in Southeast Asia. ASEANAPOL coordinates and supports Operation Maharlika deployments targeting terrorism financing networks across the region.'],
                ['name' => 'Operation Pangea',             'icon' => 'medication', 'desc' => 'A global INTERPOL-led operation targeting the online sale of counterfeit and substandard medicines and medical devices. ASEANAPOL supports Southeast Asian participation.'],
                ['name' => 'Project Torii',                'icon' => 'manage_search', 'desc' => 'An INTERPOL initiative focused on identifying and disrupting criminal networks engaged in organised crime and serious financial offences in the Asia Pacific region.'],
                ['name' => 'ASPJOC',                       'icon' => 'wifi_protected_setup', 'desc' => 'Asia and South Pacific Joint Operations Against Cybercrime — a joint effort coordinating law enforcement agencies across the region to tackle cyber-enabled crimes.'],
                ['name' => 'I-GAIN',                       'icon' => 'hub', 'desc' => 'INTERPOL Global Analysis and Intelligence Network — ASEANAPOL participates in this intelligence-sharing framework to strengthen global criminal analysis capacity.'],
                ['name' => 'IASIWG',                       'icon' => 'settings_ethernet', 'desc' => 'INTERPOL–ASEANAPOL System Integration Working Group — ensures the e-ADS remains technically aligned with INTERPOL\'s global information systems and I-24/7 network.'],
            ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach($ops as $op)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $op['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ $op['name'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $op['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- General Assembly participation --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">INTERPOL General Assembly Participation</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">ASEANAPOL's Executive Director attends the annual INTERPOL General Assembly, which serves as the forum for bilateral meetings with member countries and partner organisations.</p>
            @php
            $igas = [
                ['session' => '92nd', 'year' => 2024, 'city' => 'Glasgow',     'country' => 'United Kingdom'],
                ['session' => '90th', 'year' => 2022, 'city' => 'New Delhi',   'country' => 'India'],
                ['session' => '89th', 'year' => 2021, 'city' => 'Istanbul',    'country' => 'Turkey'],
                ['session' => '87th', 'year' => 2018, 'city' => 'Dubai',       'country' => 'UAE'],
                ['session' => '84th', 'year' => 2015, 'city' => 'Kigali',      'country' => 'Rwanda'],
                ['session' => '83rd', 'year' => 2014, 'city' => 'Monaco',      'country' => 'Monaco'],
            ];
            @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Session</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Year</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Location</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($igas as $iga)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors">
                            <td class="px-5 py-3 font-bold text-primary dark:text-accent">{{ $iga['session'] }} IGA</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $iga['year'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $iga['city'] }}, {{ $iga['country'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
