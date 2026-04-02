@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Like-Minded Entities',
    'subtitle' => 'International organisations that collaborate with ASEANAPOL as Like-Minded Entities (LME).',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',         ['locale' => app()->getLocale()])],
        ['label' => 'Like-Minded Entities', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Explainer --}}
        <div class="max-w-3xl">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                Like-Minded Entities (LMEs) are international organisations, agencies, and bodies that share common
                goals and values with ASEANAPOL in combating transnational crime, promoting regional security,
                and strengthening law enforcement cooperation across borders.
            </p>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                Through these collaborative relationships, ASEANAPOL and its LME partners exchange information,
                coordinate joint initiatives, and build capacity across a wide range of policing and security domains.
            </p>
        </div>

        {{-- LME Grid --}}
        @php
        $lmes = [
            [
                'name'  => 'International Association for Forensic Institutes',
                'short' => 'IAFI',
                'logo'  => 'iafi.png',
            ],
            [
                'name'  => 'International Association of Police Academies',
                'short' => 'INTERPA',
                'logo'  => 'interpa.png',
            ],
            [
                'name'  => 'The Asia Pacific Medico Legal Agencies',
                'short' => 'APMLA',
                'logo'  => 'apmla.jpg',
            ],
            [
                'name'  => 'The World Border Organization, Canada',
                'short' => 'BORDERPOL',
                'logo'  => 'borderpol.png',
            ],
            [
                'name'  => 'Central Asian Regional Information and Coordination Centre',
                'short' => 'CARICC',
                'logo'  => 'caricc.png',
            ],
            [
                'name'  => 'Secretariat of the Convention on International Trade in Endangered Species of Fauna and Flora',
                'short' => 'CITES',
                'logo'  => 'cites.png',
            ],
            [
                'name'  => 'United Nations Counter-Terrorism Committee Executive Directorate',
                'short' => 'UN CTED',
                'logo'  => 'un-cted.png',
            ],
            [
                'name'  => 'Southeast European Law Enforcement Center',
                'short' => 'SELEC',
                'logo'  => 'selec.jpg',
            ],
            [
                'name'  => 'Pacific Islands Chiefs of Police Secretariat',
                'short' => 'PICP',
                'logo'  => 'picp.png',
            ],
            [
                'name'  => 'European Association for Secure Transactions',
                'short' => 'EAST',
                'logo'  => 'east.png',
            ],
            [
                'name'  => 'United Nations Office on Drugs and Crime',
                'short' => 'UNODC',
                'logo'  => 'unodc.png',
            ],
            [
                'name'  => 'Regional Anti-Terrorism Structure of the Shanghai Cooperation Organisation',
                'short' => 'RATS-SCO',
                'logo'  => 'rats-sco.png',
            ],
            [
                'name'  => 'Association of Police Training Institutions in Asia',
                'short' => 'APTA',
                'logo'  => 'apta.jpeg',
            ],
            [
                'name'  => 'U.S. Agency for International Development Wildlife Asia',
                'short' => 'USAID Wildlife Asia',
                'logo'  => 'usaid.png',
            ],
            [
                'name'  => 'Rashtriya Raksha University',
                'short' => 'RRU',
                'logo'  => 'rru.png',
            ],
            [
                'name'  => 'Mastercard Asia Pacific',
                'short' => 'Mastercard',
                'logo'  => 'mastercard.png',
            ],
            [
                'name'  => 'Freeland',
                'short' => 'Freeland',
                'logo'  => 'freeland.jpeg',
            ],
            [
                'name'  => 'Lusaka Agreement Task Force',
                'short' => 'LATF',
                'logo'  => 'latf.png',
            ],
        ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($lmes as $lme)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center gap-3">
                <div class="w-full h-20 flex items-center justify-center">
                    <img src="{{ asset('media/lme/' . $lme['logo']) }}"
                         alt="{{ $lme['short'] }} logo"
                         class="max-h-20 max-w-full object-contain">
                </div>
                <div>
                    <p class="font-bold text-xs text-primary dark:text-accent">{{ $lme['short'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-snug mt-0.5">{{ $lme['name'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endsection
