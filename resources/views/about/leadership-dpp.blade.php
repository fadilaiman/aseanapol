@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Director for Plans and Programmes',
    'subtitle' => 'The Director for Plans and Programmes oversees strategic planning and programme implementation within the ASEANAPOL Secretariat.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',           ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',       ['locale' => app()->getLocale()])],
        ['label' => 'Leadership',       'url' => route('about.leadership',  ['locale' => app()->getLocale()])],
        ['label' => 'Director for Plans and Programmes', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- ── Current Director for Plans and Programmes ─────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-8">Current Director for Plans and Programmes</h2>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col sm:flex-row gap-0">
                    {{-- Photo --}}
                    <div class="sm:w-56 flex-shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ asset('media/dpp/no7.png') }}"
                             alt="Director for Plans and Programmes"
                             class="w-full sm:h-full object-cover object-top max-h-64 sm:max-h-none">
                    </div>
                    {{-- Info --}}
                    <div class="p-8 flex flex-col justify-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-accent mb-2">Director for Plans and Programmes · Present</span>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Police Colonel Jean Mary A. Mangahis</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Philippine National Police (PNP) &nbsp;·&nbsp; 🇵🇭 Philippines</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Police Colonel Jean Mary A. Mangahis serves as the Director for Plans and Programmes of the ASEANAPOL Secretariat,
                            nominated by the Philippine National Police. She is responsible for strategic planning and overseeing programme
                            implementation within the Secretariat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Past Directors for Plans and Programmes ───────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2">Past Directors for Plans and Programmes</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
                The Director for Plans and Programmes post rotates among ASEANAPOL member countries within the Secretariat.
            </p>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 w-12">#</th>
                            <th class="px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16"></th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Name &amp; Rank</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">Country</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                        $directors = [
                            ['no'=>'7th','name'=>'Pol. Col. Jean Mary A. Mangahis',      'force'=>'Philippine National Police (PNP)',  'flag'=>'🇵🇭','photo'=>asset('media/dpp/no7.png')],
                            ['no'=>'6th','name'=>'Pol. Snr. Lt. Col. Nguyen Huu Ngoc',   'force'=>'Vietnam Police Force (VPF)',        'flag'=>'🇻🇳','photo'=>asset('media/dpp/no6.png')],
                            ['no'=>'5th','name'=>'Supt. Tang Yik Tung',                  'force'=>'Royal Brunei Police Force (RBPF)', 'flag'=>'🇧🇳','photo'=>asset('media/dpp/no5.png')],
                            ['no'=>'4th','name'=>'ACP Aidah Othman',                     'force'=>'Royal Malaysia Police (RMP)',      'flag'=>'🇲🇾','photo'=>asset('media/dpp/no4.png')],
                            ['no'=>'3rd','name'=>'Pol. Supt. Yuli Cahyanti',             'force'=>'Indonesian National Police (INP)', 'flag'=>'🇮🇩','photo'=>asset('media/dpp/no3.png')],
                            ['no'=>'2nd','name'=>'Pol. Supt. Rienhard Hutagaol',         'force'=>'Indonesian National Police (INP)', 'flag'=>'🇮🇩','photo'=>asset('media/dpp/no2.png')],
                            ['no'=>'1st','name'=>'Pol. Supt. Desy Andriany',             'force'=>'Indonesian National Police (INP)', 'flag'=>'🇮🇩','photo'=>asset('media/dpp/no1.png')],
                        ];
                        @endphp
                        @foreach($directors as $d)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors">
                            <td class="px-5 py-3 font-bold text-primary dark:text-accent">{{ $d['no'] }}</td>
                            <td class="px-3 py-3">
                                <img src="{{ $d['photo'] }}" alt="{{ $d['name'] }}" class="w-10 h-12 object-cover object-top rounded-lg shadow-sm">
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $d['name'] }}</span>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $d['flag'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
