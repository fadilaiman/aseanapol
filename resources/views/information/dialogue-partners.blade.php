@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Dialogue Partners',
    'subtitle' => 'International law enforcement organisations with formal dialogue status with ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',              ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',          ['locale' => app()->getLocale()])],
        ['label' => 'Dialogue Partners','url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                Dialogue Partners are international law enforcement organisations and agencies that maintain formal cooperative
                relationships with ASEANAPOL. They participate in annual conferences and meetings, contribute to capacity-building
                programmes, and engage in joint initiatives targeting transnational crime. ASEANAPOL currently has
                <strong class="text-primary dark:text-accent">12 Dialogue Partners</strong>.
            </p>
        </div>

        @php
        $partners = [
            ['name' => 'INTERPOL',                             'full' => 'International Criminal Police Organization',            'flag' => null,  'since' => null,   'icon' => 'travel_explore',    'desc' => 'The world\'s largest international police organisation. ASEANAPOL\'s e-ADS operates on INTERPOL\'s I-24/7 secure communications network, enabling seamless criminal intelligence exchange across the region.'],
            ['name' => 'Australian Federal Police (AFP)',      'full' => 'Australian Federal Police',                             'flag' => '🇦🇺', 'since' => '2008', 'icon' => 'local_police',      'desc' => 'A founding Dialogue Partner since 2008, AFP provides capacity-building training for ASEANAPOL member countries and participates actively in annual conferences and contact person meetings.'],
            ['name' => 'Ministry of Public Security (MPS)',   'full' => 'Ministry of Public Security, China',                    'flag' => '🇨🇳', 'since' => null,   'icon' => 'security',          'desc' => 'China\'s national law enforcement ministry contributes expertise in combating transnational crime, sharing intelligence and supporting ASEANAPOL\'s regional security agenda.'],
            ['name' => 'National Police Agency (NPA)',         'full' => 'National Police Agency, Japan',                         'flag' => '🇯🇵', 'since' => null,   'icon' => 'policy',            'desc' => 'Japan\'s NPA engages as a key Dialogue Partner, contributing to cybercrime, drug trafficking, and financial crime cooperation efforts across the ASEAN region.'],
            ['name' => 'Korean National Police Agency (KNPA)','full' => 'Korean National Police Agency, Republic of Korea',      'flag' => '🇰🇷', 'since' => null,   'icon' => 'badge',             'desc' => 'KNPA supports ASEANAPOL through shared expertise in cybercrime investigation, digital forensics, and capacity-building programmes for member country police forces.'],
            ['name' => 'New Zealand Police (NZP)',             'full' => 'New Zealand Police',                                    'flag' => '🇳🇿', 'since' => null,   'icon' => 'local_police',      'desc' => 'New Zealand Police actively participates in ASEANAPOL conferences and training cooperation meetings, supporting the region\'s capacity in serious crime investigation.'],
            ['name' => 'Ministry of Internal Affairs (MIA)',  'full' => 'Ministry of Internal Affairs, Russian Federation',      'flag' => '🇷🇺', 'since' => '2014', 'icon' => 'gavel',             'desc' => 'Elevated to Dialogue Partner status at the 34th ASEANAPOL Conference in 2014. Russia\'s MIA provides annual training programmes on counter-narcotics and organised crime for AMC officers.'],
            ['name' => 'Turkish National Police (TNP)',        'full' => 'Turkish National Police',                               'flag' => '🇹🇷', 'since' => null,   'icon' => 'shield_person',     'desc' => 'Turkey\'s national police service engages with ASEANAPOL through knowledge exchange and cooperation on counter-terrorism, drug trafficking, and financial crime.'],
            ['name' => 'UK National Crime Agency (NCA)',       'full' => 'National Crime Agency, United Kingdom',                 'flag' => '🇬🇧', 'since' => null,   'icon' => 'manage_search',     'desc' => 'The UK\'s NCA collaborates with ASEANAPOL on combating serious and organised crime, including human trafficking, cybercrime, and drug networks affecting the ASEAN region.'],
            ['name' => 'Royal Canadian Mounted Police (RCMP)','full' => 'Royal Canadian Mounted Police',                         'flag' => '🇨🇦', 'since' => null,   'icon' => 'local_police',      'desc' => 'The RCMP supports ASEANAPOL capacity-building through training and knowledge transfer programmes, particularly through initiatives hosted at the Jakarta Centre for Law Enforcement Cooperation (JCLEC).'],
            ['name' => 'ASEAN Secretariat',                    'full' => 'Secretariat of the Association of Southeast Asian Nations','flag' => null,  'since' => null,   'icon' => 'hub',               'desc' => 'The ASEAN Secretariat participates as a Dialogue Partner, supporting alignment between ASEANAPOL\'s work and broader ASEAN frameworks on transnational crime and regional security.'],
            ['name' => 'Europol',                              'full' => 'European Union Agency for Law Enforcement Cooperation', 'flag' => null,  'since' => null,   'icon' => 'public',            'desc' => 'Europol engages with ASEANAPOL on cross-regional threats including financial fraud, cybercrime, and organised crime affecting both Southeast Asia and Europe.'],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($partners as $partner)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-11 h-11 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    @if($partner['flag'])
                    <span class="text-2xl leading-none">{{ $partner['flag'] }}</span>
                    @else
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $partner['icon'] }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $partner['name'] }}</h3>
                        @if($partner['since'])
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">Since {{ $partner['since'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">{{ $partner['full'] }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">{{ $partner['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to About ASEANAPOL
            </a>
        </div>
    </div>
</section>
@endsection
