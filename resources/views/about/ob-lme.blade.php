@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Observer Organisations',
    'subtitle' => 'International organisations and law enforcement agencies with Observer Organisation (OB) status in ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',         ['locale' => app()->getLocale()])],
        ['label' => 'OB / LME',         'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Explainer --}}
        <div class="max-w-3xl">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                Observer Organisations (OBs) are international law enforcement bodies and organisations that hold formal
                observer status with ASEANAPOL. Unlike Dialogue Partners, Observer Organisations participate in
                ASEANAPOL activities as observers — facilitating information exchange and exploring future cooperative
                relationships without full partnership obligations.
            </p>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL currently has <strong class="text-primary dark:text-accent">11 Observer Organisations</strong>.
                Observer status is governed by the ASEANAPOL Policy Guidelines for Accepting Observers and Dialogue Partners,
                and admission is approved by the ASEANAPOL Conference.
            </p>
        </div>

        {{-- Confirmed Observer Organisations --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2">Confirmed Observer Organisations</h2>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">
                The following organisations have been confirmed as Observer Organisations through ASEANAPOL news reports and conference records.
                The full official listing is maintained by the ASEANAPOL Permanent Secretariat.
            </p>
            @php
            $obs = [
                ['name' => 'Federal Bureau of Investigation (FBI)',        'full' => 'United States Federal Bureau of Investigation', 'flag' => '🇺🇸', 'desc' => 'The FBI participates as an Observer Organisation, engaging with ASEANAPOL on cross-border investigations, cybercrime, counter-terrorism, and capacity-building initiatives relevant to the Southeast Asian region.'],
                ['name' => 'Gulf Cooperation Council Police (GCCPOL)',     'full' => 'Gulf Cooperation Council Police',               'flag' => '🌍', 'desc' => 'GCCPOL holds observer status, supporting cross-regional law enforcement cooperation between Southeast Asia and Gulf Cooperation Council member states.'],
                ['name' => 'Ministry of Interior, Italy',                  'full' => 'Ministry of Interior of the Italian Republic', 'flag' => '🇮🇹', 'desc' => 'Italy\'s Ministry of Interior engages with ASEANAPOL as an Observer Organisation, contributing European law enforcement expertise and fostering bilateral cooperation on transnational crime.'],
                ['name' => 'Ministry of Interior, UAE',                    'full' => 'Ministry of Interior, United Arab Emirates',   'flag' => '🇦🇪', 'desc' => 'The UAE Ministry of Interior has held observer status with ASEANAPOL since 2022, with active engagement at INTERPOL General Assemblies and bilateral meetings to strengthen cooperation on regional security.', 'since' => '2022'],
                ['name' => 'IACP',                                         'full' => 'International Association of Chiefs of Police','flag' => null,  'desc' => 'IACP serves as an ASEANAPOL Observer Organisation, co-hosting virtual meetings and events focused on international policing innovations, including the application of artificial intelligence in law enforcement.'],
                ['name' => 'Shanghai Cooperation Organization (SCO)',      'full' => 'Shanghai Cooperation Organization',             'flag' => null,  'desc' => 'The SCO participates as an observer organisation in ASEANAPOL activities, supporting cooperation on security and transnational crime issues across Eurasia and Southeast Asia.'],
                ['name' => 'United Kingdom (Observer)',                    'full' => 'UK Law Enforcement (Observer status since 2014)','flag' => '🇬🇧', 'desc' => 'The United Kingdom was accepted as an observer at the 34th ASEANAPOL Conference in 2014. The UK\'s engagement subsequently evolved into a Dialogue Partnership through the National Crime Agency (NCA).'],
            ];
            @endphp
            <div class="space-y-4">
                @foreach($obs as $ob)
                <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4 items-start">
                    <div class="w-11 h-11 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0 text-2xl leading-none">
                        {{ $ob['flag'] ?? '🏛️' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $ob['name'] }}</h3>
                            @if(!empty($ob['since']))
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">Since {{ $ob['since'] }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-1.5">{{ $ob['full'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">{{ $ob['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Admission info --}}
        <div class="bg-primary/5 dark:bg-primary/10 rounded-2xl p-6 border border-primary/10 dark:border-primary/30 max-w-3xl">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0 mt-0.5">info</span>
                <div>
                    <h3 class="font-semibold text-primary dark:text-white mb-2">Complete Observer Organisation Listing</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        The complete and current list of ASEANAPOL Observer Organisations (11 total) is maintained by the
                        Permanent Secretariat and updated following each annual ASEANAPOL Conference.
                        For the authoritative listing, please contact
                        <a href="mailto:aseanapolsec@aseanapol.org" class="text-primary dark:text-accent hover:underline font-medium">aseanapolsec@aseanapol.org</a>.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
