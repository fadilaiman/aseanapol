@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Permanent Secretariat',
    'subtitle' => 'The administrative hub of ASEANAPOL, based in Kuala Lumpur, Malaysia.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Permanent Secretariat', 'url' => ''],
    ],
])
@endsection

@section('content')

{{-- ── Introduction ──────────────────────────────────────────────────── --}}
<section class="py-14 bg-white dark:bg-dark-card border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <img src="{{ asset('images/aseanapol-logo.png') }}" alt="ASEANAPOL Logo"
             class="h-28 w-auto mx-auto mb-6">
        <h2 class="text-3xl font-extrabold text-primary dark:text-white mb-2 tracking-tight uppercase">Introduction</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-8"></div>

        <div class="text-left space-y-5 text-gray-600 dark:text-gray-300 leading-relaxed">
            <p class="font-semibold text-gray-800 dark:text-white text-lg">Preface</p>
            <p>The first formal meeting of the Chiefs of ASEAN Police was held in Manila, Philippines on 21 to 23 October 1981, to discuss matters of law enforcement and crime control. This annual meeting was called the <strong>ASEANAPOL Conference</strong>.</p>
            <p>The basic requirement for a country to become a member of ASEANAPOL is that the country shall first be a member of ASEAN and the application shall be tabled at the conference for approval.</p>
            <p>The founding members were <strong>Malaysia, Singapore, Thailand, Indonesia and the Philippines</strong>. Membership grew over the years:</p>
            <ul class="list-none space-y-1 pl-4 border-l-4 border-accent/30">
                <li><span class="font-semibold text-primary dark:text-accent">1984</span> — Royal Brunei Police joined the conference for the first time.</li>
                <li><span class="font-semibold text-primary dark:text-accent">1996</span> — The Republic of Vietnam National Police joined the conference.</li>
                <li><span class="font-semibold text-primary dark:text-accent">1998</span> — Lao General Department of Police and Myanmar Police Force joined the conference.</li>
                <li><span class="font-semibold text-primary dark:text-accent">2000</span> — Cambodia National Police joined the conference.</li>
            </ul>
            <p>The current members of ASEANAPOL are <strong>Brunei, Cambodia, Indonesia, Lao People Democratic Republic, Malaysia, Myanmar, The Philippines, Singapore, Thailand</strong> and <strong>Vietnam</strong>.</p>
        </div>
    </div>
</section>

{{-- ── Main content (full-width) ─────────────────────────────────────── --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- About --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">About the Secretariat</h2>
            <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                <p>
                    The ASEANAPOL Permanent Secretariat serves as the administrative and operational hub of the Association of Southeast Asian Nations Chiefs of Police. Established to provide continuity of organisational functions between annual conferences, the Secretariat ensures the smooth coordination of ASEANAPOL's programmes, initiatives, and communications.
                </p>
                <p>
                    The Secretariat is hosted by the Royal Malaysia Police (RMP) and is located in Kuala Lumpur, Malaysia. It is staffed by officers from ASEAN member states and is headed by an Executive Director, a senior officer nominated by the host country.
                </p>
                <p>
                    The Permanent Secretariat is responsible for maintaining ASEANAPOL's records, facilitating inter-agency communication, coordinating working committees, and supporting the planning of annual conferences and other cooperative activities.
                </p>
            </div>
        </div>

        {{-- History --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">History</h2>
            <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                <p>
                    Prior to the establishment of the Permanent Secretariat, the ASEANAPOL Secretariat operated on a rotational basis, with member countries taking turns to host the ASEANAPOL Conference and automatically assuming the role of the Secretariat for that year.
                </p>
                <p>
                    The 25th Joint Communiqué signed by the ASEAN Chiefs of Police during the 25th ASEANAPOL Conference held in Bali, Indonesia, expressly stated the need to establish a Permanent ASEANAPOL Secretariat.
                </p>
                <p class="font-semibold text-gray-800 dark:text-white">Objectives of the establishment of a Permanent Secretariat:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>To harmonise and standardise coordination and communication mechanisms amongst ASEAN police institutions;</li>
                    <li>To conduct a comprehensive and integrative study concerning the resolutions agreed in the ASEANAPOL Joint Communiqués;</li>
                    <li>To establish a mechanism with responsibility to monitor and follow up the implementation of resolutions in the Joint Communiqués; and</li>
                    <li>To transform the resolutions adopted in the Joint Communiqués into the ASEANAPOL Plan of Action and its work programme.</li>
                </ul>
                <p>The working group set up to consider the viability of the Permanent ASEANAPOL Secretariat finalised that:</p>
                <ol class="list-decimal pl-5 space-y-1">
                    <li>The Secretariat shall be administered based on the Terms of Reference;</li>
                    <li>The Head of the Secretariat is an Executive Director and he is assisted by 2 Directors.</li>
                </ol>
                <p>
                    The tenure of services are: <strong>Executive Director — 2 years</strong>; <strong>Directors — 2 to 3 years</strong>.
                </p>
                <p>
                    During the 29th ASEANAPOL Conference in Hanoi, Vietnam in 2009, the Terms of Reference on the establishment of the ASEANAPOL Secretariat was endorsed. Kuala Lumpur, Malaysia was made the permanent seat for the Secretariat.
                </p>
                <p>
                    The ASEANAPOL Secretariat was fully operational from <strong>1 January 2010</strong>.
                </p>
            </div>
        </div>

        {{-- Functions --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">Functions</h2>
            <ul class="space-y-3">
                @php
                $functions = [
                    ['icon' => 'admin_panel_settings', 'text' => 'Coordinate and implement decisions made at the ASEANAPOL Conference'],
                    ['icon' => 'groups',               'text' => 'Facilitate communication between member police forces'],
                    ['icon' => 'folder_open',          'text' => 'Maintain records, archives, and documentation of ASEANAPOL activities'],
                    ['icon' => 'calendar_month',       'text' => 'Plan and organise the annual ASEANAPOL Conference'],
                    ['icon' => 'hub',                  'text' => 'Coordinate working committees and cooperative programmes'],
                    ['icon' => 'public',               'text' => 'Manage external relations with partner organisations and dialogue partners'],
                ];
                @endphp
                @foreach($functions as $fn)
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">{{ $fn['icon'] }}</span>
                    <span class="text-gray-600 dark:text-gray-300">{{ $fn['text'] }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Back to About ASEANAPOL
        </a>

    </div>
</section>

{{-- ── Executive Director and Directors ──────────────────────────────── --}}
<section class="py-14 bg-white dark:bg-dark-card border-t border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2 text-center uppercase tracking-wide">Executive Director and Directors</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-10"></div>

        @php
        $dirPoliceServices = [
            ['name' => 'DACP Lim Kim Tak',                         'force' => 'SPF', 'flag' => '🇸🇬', 'term' => '2010–2012'],
            ['name' => 'ACP Mohamad Anil Shah bin Abdullah',        'force' => 'RMP', 'flag' => '🇲🇾', 'term' => '2013–2015'],
            ['name' => 'PSSUPT. Ferdinand R.P. Bartolome',          'force' => 'PNP', 'flag' => '🇵🇭', 'term' => '2016–2017'],
            ['name' => 'DAC Jim Wee',                               'force' => 'SPF', 'flag' => '🇸🇬', 'term' => '2018–2019'],
            ['name' => 'Pol. Snr. Supt. Joni Getamala',            'force' => 'INP', 'flag' => '🇮🇩', 'term' => '2020–2022'],
            ['name' => 'ACP Dr. Bakri Haji Zainal Abidin',         'force' => 'RMP', 'flag' => '🇲🇾', 'term' => '2023–2024'],
            ['name' => 'Pol. Snr. Supt. Huntal Tambunan',          'force' => 'INP', 'flag' => '🇮🇩', 'term' => '2024–present'],
        ];
        $dirExecutive = [
            ['name' => 'ACP Mohd Nadzri bin Zainal Abidin',                              'force' => 'RMP',  'flag' => '🇲🇾', 'term' => '2010–2011'],
            ['name' => 'Lt. General Sar Moline',                                          'force' => 'CNP',  'flag' => '🇰🇭', 'term' => '2012–2013'],
            ['name' => "SAC Pengiran Dato' Paduka Hj. Abdul Wahab bin Pengiran Hj. Omar", 'force' => 'RBPF', 'flag' => '🇧🇳', 'term' => '2014–2015'],
            ['name' => 'Pol. Insp. General Yohanes Agus Mulyono',                        'force' => 'INP',  'flag' => '🇮🇩', 'term' => '2016–2017'],
            ['name' => 'Pol. Colonel Kenechanh Phommachack',                              'force' => 'LPF',  'flag' => '🇱🇦', 'term' => '2018–2019'],
            ['name' => 'DAC Jim Wee',                                                     'force' => 'SPF',  'flag' => '🇸🇬', 'term' => '2020–2021'],
            ['name' => 'Pol. Brigadier General Zaw Lin Tun',                              'force' => 'MPF',  'flag' => '🇲🇲', 'term' => '2022–2023'],
            ['name' => 'Pol. Colonel David Martinez Vinluan',                             'force' => 'PNP',  'flag' => '🇵🇭', 'term' => '2024–present'],
        ];
        $dirPlans = [
            ['name' => 'Supt. Desy Adriani',                  'force' => 'INP',          'flag' => '🇮🇩', 'term' => '2010–2012'],
            ['name' => 'Supt. Reinhard Hutagaol',             'force' => 'INP',          'flag' => '🇮🇩', 'term' => '2013–2014'],
            ['name' => 'Supt. Yuli Cahyanti',                 'force' => 'INP',          'flag' => '🇮🇩', 'term' => '2015–2016'],
            ['name' => 'ACP Aidah binti Othman',              'force' => 'RMP',          'flag' => '🇲🇾', 'term' => '2017–2019'],
            ['name' => 'Supt. Tang Yik Tung',                 'force' => 'RBPF',         'flag' => '🇧🇳', 'term' => '2020–2022'],
            ['name' => 'Pol. Snr. Lt. Col. Nguyen Huu Ngoc', 'force' => 'OIPA Vietnam', 'flag' => '🇻🇳', 'term' => '2023–present'],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Director for Police Services --}}
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-primary px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-accent text-center">Director for Police Services</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirPoliceServices as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Executive Director --}}
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-accent/40 overflow-hidden shadow-md">
                <div class="bg-accent px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary text-center">Executive Director</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirExecutive as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Director for Plans and Programmes --}}
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-primary px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-accent text-center">Director for Plans and Programmes</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirPlans as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
