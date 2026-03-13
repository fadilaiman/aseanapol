@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Chronology',
    'subtitle' => 'The history of ASEANAPOL from its founding in 1981 to the present day.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Chronology',      'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @php
        $milestones = [
            [
                'year'  => '1981',
                'title' => '1st ASEANAPOL Conference — Manila, Philippines',
                'desc'  => 'ASEANAPOL was founded on 10 June 1981 at the 1st ASEANAPOL Conference held in Manila, Philippines. The founding member states were Brunei Darussalam, Indonesia, Malaysia, the Philippines, Singapore, and Thailand.',
            ],
            [
                'year'  => '1984',
                'title' => 'Expansion of Membership',
                'desc'  => 'ASEANAPOL continued to grow in membership as additional ASEAN nations formalised their participation in the organisation\'s cooperative framework.',
            ],
            [
                'year'  => '1997',
                'title' => 'Admission of New Members',
                'desc'  => 'Following ASEAN\'s expansion, Myanmar, Lao PDR, and Vietnam joined ASEANAPOL, broadening the regional police cooperation network across mainland Southeast Asia.',
            ],
            [
                'year'  => '1999',
                'title' => 'Cambodia Joins ASEANAPOL',
                'desc'  => 'The Cambodian National Police formally joined ASEANAPOL, completing the full ten-member configuration of the organisation aligned with all ASEAN member states.',
            ],
            [
                'year'  => '2004',
                'title' => 'Permanent Secretariat Established',
                'desc'  => 'The ASEANAPOL Permanent Secretariat was formally established in Kuala Lumpur, Malaysia, hosted by the Royal Malaysia Police. This marked a significant step in institutionalising ASEANAPOL\'s administrative capacity.',
            ],
            [
                'year'  => '2010s',
                'title' => 'Expanded International Partnerships',
                'desc'  => 'ASEANAPOL significantly expanded its network of dialogue partners and collaborative frameworks with international organisations including INTERPOL, UNODC, and various bilateral partners to enhance capacity building and joint operations.',
            ],
            [
                'year'  => '2020s',
                'title' => 'Modernisation and Digital Cooperation',
                'desc'  => 'ASEANAPOL embraced digital transformation in police cooperation, enhancing the ASEANAPOL Database system (e-ADS), strengthening cybercrime cooperation frameworks, and adapting to emerging transnational threats.',
            ],
            [
                'year'  => '2025',
                'title' => '44th ASEANAPOL Conference',
                'desc'  => 'ASEANAPOL continues to grow with the 44th Annual Conference and ongoing cooperation programmes. The organisation remains the premier platform for police cooperation in the ASEAN region.',
            ],
        ];
        @endphp

        <div class="relative">
            {{-- Vertical line --}}
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-primary/20 dark:bg-primary/40 hidden sm:block"></div>

            <div class="space-y-8">
                @foreach($milestones as $item)
                <div class="relative flex gap-6 sm:gap-8">
                    {{-- Year dot --}}
                    <div class="hidden sm:flex flex-shrink-0 w-12 h-12 bg-primary dark:bg-accent rounded-full items-center justify-center z-10 mt-1">
                        <span class="material-symbols-outlined text-white dark:text-primary text-xl">radio_button_checked</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block bg-primary/10 dark:bg-primary/30 text-primary dark:text-accent font-bold text-sm px-3 py-1 rounded-full">{{ $item['year'] }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $item['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to About ASEANAPOL
            </a>
        </div>
    </div>
</section>
@endsection
