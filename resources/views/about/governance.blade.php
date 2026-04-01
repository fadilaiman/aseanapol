@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Governance',
    'subtitle' => 'The organisational structure and decision-making bodies of ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Governance',      'url' => ''],
    ],
])
@endsection

@section('content')

{{-- ── Organisational Chart ────────────────────────────────────────────── --}}
<section class="py-14 bg-white dark:bg-dark-card border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2 tracking-tight uppercase">Organisational Structure of ASEANAPOL</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-8"></div>
        <img src="{{ asset('media/governance/governance/organisational-chart-2025.jpg') }}"
             alt="Organisational Structure of ASEANAPOL"
             class="mx-auto w-full max-w-4xl rounded-lg shadow">
    </div>
</section>

{{-- ── Main content ────────────────────────────────────────────────────── --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- ASEANAPOL Conference --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">ASEANAPOL Conference</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                <p>The Conference shall be held annually on a rotational basis amongst member countries. The Conference will be attended by the Chiefs of Police.</p>
            </div>
        </div>

        {{-- ASEANAPOL Executive Committee --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">ASEANAPOL Executive Committee</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                <p>The Executive Committee shall comprise of Deputy Heads of delegation attending the annual Conference.</p>
                <p>The Executive Committee shall meet annually, immediately before the Conference.</p>
                <p>The Executive Director of the ASEANAPOL Secretariat shall present a report of its activities, including amongst others, issues on financial performance, procurement of works, supplies and services and control and management of contracts, to the Executive Committee.</p>
                <p>The Executive Committee shall provide a summary report of the activities of the Secretariat to the Heads of Delegation at the Conference and at the Closing Plenary Session of the Conference.</p>
            </div>
        </div>

        {{-- ASEANAPOL Secretariat Directors --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">ASEANAPOL Secretariat Directors</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                <p>The Executive Director of the Secretariat shall be appointed by the ASEANAPOL Conference upon nomination on a rotational basis in alphabetical order for a term of two years. The Executive Director of the Secretariat shall be a Senior Police Officer of the rank of Brigadier General and above or its equivalent. The Executive Director will be assisted by Director for Police Services and Director for Plans and Programmes shall be a Senior Police Officer of the rank of Colonel and above or its equivalent.</p>
            </div>
        </div>

        {{-- ASEANAPOL Conference since 1981 --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-6 uppercase">ASEANAPOL Conference (since 1981)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-3 font-semibold text-center w-12">#</th>
                            <th class="px-4 py-3 font-semibold">Dates</th>
                            <th class="px-4 py-3 font-semibold text-center">Year</th>
                            <th class="px-4 py-3 font-semibold">City</th>
                            <th class="px-4 py-3 font-semibold">Country</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                        $conferences = [
                            [1,  '21 - 23 October',     1981, 'Manila',              'The Philippines'],
                            [2,  '18 - 20 June',        1982, 'Pattaya',             'Thailand'],
                            [3,  '16 - 19 November',    1983, 'Jakarta',             'Indonesia'],
                            [4,  '21 - 24 November',    1984, 'Kuala Lumpur',        'Malaysia'],
                            [5,  '7 - 9 November',      1985, 'Singapore',           'Singapore'],
                            [6,  '11 - 13 November',    1986, 'Bandar Seri Begawan', 'Brunei'],
                            [7,  '11 - 18 September',   1987, 'Manila',              'The Philippines'],
                            [8,  '14 - 16 November',    1988, 'Chiang Mai',          'Thailand'],
                            [9,  '1 - 6 November',      1989, 'Bali',                'Indonesia'],
                            [10, '25 - 28 July',        1990, 'Kuala Lumpur',        'Malaysia'],
                            [11, '5 - 9 May',           1991, 'Singapore',           'Singapore'],
                            [12, '3 - 5 August',        1992, 'Brunei',              'Brunei'],
                            [13, '19 - 21 April',       1993, 'Manila',              'The Philippines'],
                            [14, '1 - 5 May',           1994, 'Phuket',              'Thailand'],
                            [15, '21 - 25 May',         1995, 'Jakarta',             'Indonesia'],
                            [16, '26 - 30 May',         1996, 'Kuala Lumpur',        'Malaysia'],
                            [17, '1 - 5 June',          1997, 'Singapore',           'Singapore'],
                            [18, '24 - 27 May',         1998, 'Brunei',              'Brunei'],
                            [19, '26 - 28 April',       1999, 'Hanoi',               'Viet Nam'],
                            [20, '8 - 10 May',          2000, 'Yangon',              'Myanmar'],
                            [21, '23 - 25 May',         2001, 'Vientiane',           'Lao PDR'],
                            [22, '28 - 30 May',         2002, 'Phnom Penh',          'Cambodia'],
                            [23, '8 - 12 September',    2003, 'Manila',              'The Philippines'],
                            [24, '7 - 11 June',         2004, 'Chiang Mai',          'Thailand'],
                            [25, '16 - 20 May',         2005, 'Bali',                'Indonesia'],
                            [26, '22 - 26 May',         2006, 'Kuala Lumpur',        'Malaysia'],
                            [27, '2 - 7 June',          2007, 'Singapore',           'Singapore'],
                            [28, '25 - 29 May',         2008, 'Bandar Seri Begawan', 'Brunei'],
                            [29, '12 - 16 May',         2009, 'Hanoi',               'Viet Nam'],
                            [30, '24 - 28 May',         2010, 'Phnom Penh',          'Cambodia'],
                            [31, '31 May - 2 June',     2011, 'Vientiane',           'Lao PDR'],
                            [32, '22 - 24 May',         2012, 'Nay Pyi Taw',         'Myanmar'],
                            [33, '21 - 23 February',    2013, 'Pattaya',             'Thailand'],
                            [34, '12 - 16 May',         2014, 'Manila',              'The Philippines'],
                            [35, '3 - 7 August',        2015, 'Jakarta',             'Indonesia'],
                            [36, '24 - 29 July',        2016, 'Kuala Lumpur',        'Malaysia'],
                            [37, '12 - 14 September',   2017, 'Singapore',           'Singapore'],
                            [38, '3 - 5 September',     2018, 'Bandar Seri Begawan', 'Brunei'],
                            [39, '14 - 16 September',   2019, 'Hanoi',               'Viet Nam'],
                            [40, '1 - 5 March',         2022, 'Phnom Penh',          'Cambodia'],
                            [41, '16 - 20 October',     2023, 'Vientiane',           'Lao PDR'],
                            [42, '21 - 25 October',     2024, 'Nay Pyi Taw',         'Myanmar'],
                        ];
                        @endphp
                        @foreach($conferences as $i => $c)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-dark-card' : 'bg-gray-50 dark:bg-dark-surface' }}">
                            <td class="px-4 py-2 text-center font-semibold text-primary dark:text-accent">{{ $c[0] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c[1] }}</td>
                            <td class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">{{ $c[2] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c[3] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c[4] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to About ASEANAPOL
            </a>
        </div>

    </div>
</section>
@endsection
