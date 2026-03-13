@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'International Collaboration',
    'subtitle' => 'ASEANAPOL\'s network of international partner organisations working together to combat transnational crime.',
    'breadcrumbs' => [
        ['label' => 'Home',                       'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'International Collaboration', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-12">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                ASEANAPOL maintains active collaborative relationships with a broad range of international organisations across law enforcement, crime prevention, wildlife protection, and financial security. These partnerships strengthen ASEANAPOL's capacity to address complex transnational threats.
            </p>
        </div>

        @php
        $partners = [
            [
                'name'    => 'INTERPA',
                'full'    => 'International Association of Police Academies',
                'icon'    => 'school',
                'website' => null,
                'desc'    => 'Fosters cooperation among police academies worldwide, supporting training, educational exchange, and the development of police professionalism.',
                'category'=> 'Law Enforcement Training',
            ],
            [
                'name'    => 'APMLA',
                'full'    => 'Asia Pacific Medico Legal Agencies',
                'icon'    => 'medical_services',
                'website' => null,
                'desc'    => 'Promotes cooperation among medico-legal agencies in the Asia Pacific region, supporting forensic science development and criminal investigations.',
                'category'=> 'Forensics & Science',
            ],
            [
                'name'    => 'BorderPol',
                'full'    => 'World Border Organization',
                'icon'    => 'border_style',
                'website' => null,
                'desc'    => 'A global network of border management agencies promoting cooperation, information exchange, and best practices in border security and control (Canada).',
                'category'=> 'Border Security',
            ],
            [
                'name'    => 'CARICC',
                'full'    => 'Central Asian Regional Information and Coordination Centre',
                'icon'    => 'hub',
                'website' => null,
                'desc'    => 'Facilitates international cooperation in combating illicit drug trafficking, organised crime, and terrorism in Central Asia.',
                'category'=> 'Drug Trafficking',
            ],
            [
                'name'    => 'CITES Secretariat',
                'full'    => 'Convention on International Trade in Endangered Species',
                'icon'    => 'nature',
                'website' => 'https://cites.org',
                'desc'    => 'Regulates international trade in wild animals and plants to prevent overexploitation and ensure species survival.',
                'category'=> 'Wildlife Protection',
            ],
            [
                'name'    => 'UN CTED',
                'full'    => 'UN Counter Terrorism Committee Executive Directorate',
                'icon'    => 'security',
                'website' => 'https://www.un.org/sc/ctc',
                'desc'    => 'Supports the UN Security Council in monitoring and facilitating the implementation of counter-terrorism resolutions by member states.',
                'category'=> 'Counter-Terrorism',
            ],
            [
                'name'    => 'SELEC',
                'full'    => 'Southeast European Law Enforcement Center',
                'icon'    => 'gavel',
                'website' => 'https://www.selec.org',
                'desc'    => 'Enhances cooperation in combating transnational crime including terrorism, organised crime, and corruption in Southeast Europe.',
                'category'=> 'Law Enforcement',
            ],
            [
                'name'    => 'Pacific Islands Chiefs of Police',
                'full'    => 'Pacific Islands Chiefs of Police Secretariat',
                'icon'    => 'local_police',
                'website' => 'https://picp.net',
                'desc'    => 'The regional police organisation for the Pacific Islands, promoting police cooperation and capacity building across Pacific island nations.',
                'category'=> 'Regional Police',
            ],
            [
                'name'    => 'EAST',
                'full'    => 'European Association for Secure Transactions',
                'icon'    => 'credit_card',
                'website' => 'https://east.eu.com',
                'desc'    => 'Works to reduce payment and transaction fraud through information sharing, research, and cooperation between law enforcement and the financial sector.',
                'category'=> 'Financial Crime',
            ],
            [
                'name'    => 'UNODC',
                'full'    => 'United Nations Office on Drugs and Crime',
                'icon'    => 'balance',
                'website' => 'https://www.unodc.org',
                'desc'    => 'Assists member states in the fight against illicit drugs, crime and terrorism, and promotes the rule of law and justice.',
                'category'=> 'Drugs & Crime',
            ],
            [
                'name'    => 'RATS-SCO',
                'full'    => 'Regional Anti-Terrorist Structure of the Shanghai Cooperation Organisation',
                'icon'    => 'policy',
                'website' => null,
                'desc'    => 'A permanent body of the SCO facilitating coordination among member states in the fight against terrorism, separatism, and extremism.',
                'category'=> 'Counter-Terrorism',
            ],
            [
                'name'    => 'APTA',
                'full'    => 'ASEAN Police Training Association',
                'icon'    => 'school',
                'website' => null,
                'desc'    => 'Supports professional development and training initiatives for police officers across ASEAN member states.',
                'category'=> 'Training',
            ],
            [
                'name'    => 'USAID Wildlife Asia',
                'full'    => 'USAID Wildlife Asia Programme',
                'icon'    => 'pets',
                'website' => null,
                'desc'    => 'Supports wildlife crime enforcement in Asia, working with governments and organisations to reduce the illegal trade in wildlife.',
                'category'=> 'Wildlife Protection',
            ],
            [
                'name'    => 'RRU',
                'full'    => 'Regional Response Unit',
                'icon'    => 'emergency',
                'website' => null,
                'desc'    => 'Supports rapid regional response capabilities and coordination in addressing emerging transnational security threats.',
                'category'=> 'Crisis Response',
            ],
            [
                'name'    => 'Mastercard',
                'full'    => 'Mastercard International',
                'icon'    => 'payments',
                'website' => 'https://www.mastercard.com',
                'desc'    => 'Collaboration focused on combating financial fraud, cybercrime, and digital payment security threats affecting the region.',
                'category'=> 'Financial Security',
            ],
            [
                'name'    => 'Freeland',
                'full'    => 'Freeland Foundation',
                'icon'    => 'forest',
                'website' => 'https://www.freeland.org',
                'desc'    => 'Combats wildlife trafficking and human trafficking in Asia through partnerships with law enforcement, including the TRIPOD II programme with ASEANAPOL.',
                'category'=> 'Wildlife & Human Trafficking',
            ],
            [
                'name'    => 'LATF',
                'full'    => 'ASEAN Law Enforcement Task Force',
                'icon'    => 'task',
                'website' => null,
                'desc'    => 'Coordinates law enforcement task force activities addressing specific transnational crime priorities across ASEAN.',
                'category'=> 'Law Enforcement',
            ],
        ];

        $categories = collect($partners)->pluck('category')->unique()->values();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($partners as $partner)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-primary/8 dark:bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $partner['icon'] }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $partner['name'] }}</h3>
                            <span class="text-xs bg-accent/15 text-accent font-medium px-2 py-0.5 rounded-full">{{ $partner['category'] }}</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5">{{ $partner['full'] }}</p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed flex-1">{{ $partner['desc'] }}</p>
                @if(!empty($partner['website']))
                <a href="{{ $partner['website'] }}" target="_blank" rel="noopener"
                   class="mt-3 inline-flex items-center gap-1 text-primary dark:text-accent text-xs font-medium hover:underline">
                    <span class="material-symbols-outlined text-sm">open_in_new</span> Visit Website
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
