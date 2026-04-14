@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Contact Us',
    'subtitle' => 'Get in touch with the ASEANAPOL Permanent Secretariat.',
    'breadcrumbs' => [
        ['label' => 'Home',       'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Contact Us', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$members = [
    ['flag' => 'bn', 'country' => 'Brunei Darussalam',  'org' => 'Royal Brunei Police Force (RBPF)',                        'tel' => '+673-2459 500',                'fax' => '+673 245 9527',   'email' => 'rbpf.interpol@police.gov.bn'],
    ['flag' => 'kh', 'country' => 'Cambodia',           'org' => 'Cambodian National Police (CNP)',                         'tel' => '+855 23216585',                'fax' => '+855 2321 6585', 'email' => 'camcontactperson@gmail.com'],
    ['flag' => 'id', 'country' => 'Indonesia',          'org' => 'Indonesia National Police (INP)',                        'tel' => '+021 739 3650',                'fax' => '+021 720 1402',  'email' => 'ncb-jakarta@interpol.go.id'],
    ['flag' => 'la', 'country' => 'Lao PDR',            'org' => 'Lao Police Force (LPF)',                                  'tel' => '+85 6213 16323',               'fax' => '+856 2131 6323', 'email' => 'ncbvientiane@gmail.com'],
    ['flag' => 'my', 'country' => 'Malaysia',           'org' => 'Royal Malaysia Police (RMP)',                             'tel' => '+603 2266 2222',               'fax' => '+603 2070 7500', 'email' => 'rmp@rmp.gov.my'],
    ['flag' => 'mm', 'country' => 'Myanmar',            'org' => 'Myanmar Police Force (MPF)',                              'tel' => '+95 6741 2066',                'fax' => '+95 6741 2188',  'email' => 'naypyitaw.ncb@gmail.com'],
    ['flag' => 'ph', 'country' => 'Philippines',        'org' => 'Philippines National Police (PNP)',                       'tel' => '+632 8723 0401',               'fax' => '+632 7218549',   'email' => 'laiad.dpl@pnp.gov.ph'],
    ['flag' => 'sg', 'country' => 'Singapore',          'org' => 'Singapore Police Force (SPF)',                            'tel' => '1800 358 000',                 'fax' => '+65 6256 1296',  'email' => null, 'web' => 'https://www.police.gov.sg/e-services'],
    ['flag' => 'th', 'country' => 'Thailand',           'org' => 'Royal Thai Police (RTP)',                                 'tel' => '+6622053001',                  'fax' => '+6622533856',    'email' => 'aseanapol.th@gmail.com'],
    ['flag' => 'vn', 'country' => 'Viet Nam',           'org' => 'Vietnam Police Force (VPF)',                              'tel' => '+8424 3938 7173',              'fax' => '+8424 3938 7176', 'email' => 'division6@dfir.gov.vn'],
    ['flag' => 'tl', 'country' => 'Timor-Leste',        'org' => 'National Police of Timor-Leste (PNTL)',                  'tel' => '—',                           'fax' => '—',              'email' => null],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- Top two-column: Secretariat Info + Inquiry Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- 9.2 — Secretariat Contact Information --}}
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-1">Permanent Secretariat</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ASEANAPOL Headquarters, Kuala Lumpur</p>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700/50 overflow-hidden">
                    {{-- Address --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">location_on</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Address</div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Level 13, Tower 2, Bank Rakyat Twin Tower<br>
                                No. 33, Jalan Rakyat<br>
                                50470 Kuala Lumpur, Malaysia
                            </p>
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">call</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phone</div>
                            <a href="tel:+60322602222" class="text-sm text-primary dark:text-accent hover:underline">+603-22602222</a>
                        </div>
                    </div>
                    {{-- Fax --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">fax</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Fax</div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">+603-22602205</span>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">mail</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email</div>
                            <a href="mailto:aseanapolsec@aseanapol.org" class="text-sm text-primary dark:text-accent hover:underline">aseanapolsec@aseanapol.org</a>
                        </div>
                    </div>
                </div>

                {{-- Office Hours note --}}
                <div class="bg-primary/5 dark:bg-primary/15 rounded-xl p-4 border border-primary/10 dark:border-primary/20 text-sm text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined text-base text-primary dark:text-accent align-middle mr-1">schedule</span>
                    Office hours: Monday–Friday, 8:30 AM – 5:30 PM (MYT, UTC+8)
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full min-h-[420px]">
                    <iframe
                        src="https://maps.google.com/maps?q=Menara+Kembar+Bank+Rakyat,+33+Jalan+Rakyat,+Kuala+Lumpur&output=embed&z=17"
                        width="100%"
                        height="100%"
                        style="min-height: 420px; border: 0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- 9.4 — Member Directory --}}
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">contacts</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-primary dark:text-white">Member Country Directory</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">INTERPOL/ASEANAPOL liaison contact points for all 11 member states.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead class="bg-gray-50 dark:bg-dark-surface border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-48">Country</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Organisation</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-44 hidden md:table-cell">Phone</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-36 hidden lg:table-cell">Fax</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-52">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach($members as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ asset('images/flags/' . $m['flag'] . '.svg') }}"
                                             onerror="this.style.display='none'"
                                             alt="{{ $m['country'] }}" class="w-6 h-4 object-cover rounded-sm flex-shrink-0">
                                        <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $m['country'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 text-xs leading-relaxed">{{ $m['org'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 text-xs hidden md:table-cell">{{ $m['tel'] }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs hidden lg:table-cell">{{ $m['fax'] ?? '—' }}</td>
                                <td class="px-6 py-4 text-xs">
                                    @if(!empty($m['email']))
                                    <a href="mailto:{{ $m['email'] }}" class="text-primary dark:text-accent hover:underline break-all">{{ $m['email'] }}</a>
                                    @elseif(!empty($m['web']))
                                    <a href="{{ $m['web'] }}" target="_blank" rel="noopener" class="text-primary dark:text-accent hover:underline">e-Services portal</a>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
