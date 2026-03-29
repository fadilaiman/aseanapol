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
    ['flag' => 'bn', 'country' => 'Brunei Darussalam',  'org' => 'Royal Brunei Police Force',                               'tel' => '+673-2459 500 (ext: 784/785)', 'fax' => '+673-2423 901',  'email' => 'rbpf.interpol@police.gov.bn'],
    ['flag' => 'kh', 'country' => 'Cambodia',           'org' => 'Cambodian National Police',                               'tel' => '—',                           'fax' => '—',              'email' => 'camcontactperson@gmail.com'],
    ['flag' => 'id', 'country' => 'Indonesia',          'org' => 'Indonesian National Police (POLRI)',                      'tel' => '+021 739 3650 / +021 721 8467','fax' => '+021 720 1402',  'email' => 'ncb-jakarta@interpol.go.id'],
    ['flag' => 'la', 'country' => 'Lao PDR',            'org' => 'Lao Police Force',                                        'tel' => '+856 2131 6323',               'fax' => '+856 2131 6323', 'email' => 'ncbvientiane@gmail.com'],
    ['flag' => 'my', 'country' => 'Malaysia',           'org' => 'Royal Malaysia Police (PDRM)',                            'tel' => '+603 2266 2222',               'fax' => '+603 2070 7500', 'email' => 'rmp@rmp.gov.my'],
    ['flag' => 'mm', 'country' => 'Myanmar',            'org' => 'Myanmar Police Force',                                   'tel' => '+95 6741 2066',                'fax' => '+95 6741 2188',  'email' => 'naypyitaw.ncb@gmail.com'],
    ['flag' => 'ph', 'country' => 'Philippines',        'org' => 'Philippine National Police (PNP)',                        'tel' => '+632 8723 0401',               'fax' => '—',              'email' => 'laiad.dpl@pnp.gov.ph'],
    ['flag' => 'sg', 'country' => 'Singapore',          'org' => 'Singapore Police Force (SPF)',                            'tel' => '1800 358 000',                 'fax' => '+65 6256 1296',  'email' => null, 'web' => 'https://www.police.gov.sg/e-services'],
    ['flag' => 'th', 'country' => 'Thailand',           'org' => 'Royal Thai Police',                                      'tel' => '+6622053001',                  'fax' => '+6622053001',    'email' => 'aseanapol.th@gmail.com'],
    ['flag' => 'vn', 'country' => 'Viet Nam',           'org' => 'Office of Investigation Police Agency, Ministry of Public Security', 'tel' => '+8424 3938 7173', 'fax' => '+8424 3938 7176', 'email' => 'division6@dfir.gov.vn'],
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

            {{-- 9.3 — Inquiry Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                    <h2 class="text-xl font-bold text-primary dark:text-white mb-1">Send an Inquiry</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">We aim to respond within 2–3 business days.</p>

                    @if(session('contact_success'))
                    <div class="flex items-start gap-3 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 rounded-xl p-4 mb-6">
                        <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-xl flex-shrink-0">check_circle</span>
                        <p class="text-sm text-teal-700 dark:text-teal-300 font-medium">{{ session('contact_success') }}</p>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-4 mb-6">
                        <span class="material-symbols-outlined text-red-500 text-xl flex-shrink-0">error</span>
                        <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact-us.submit', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="contact_name" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Full Name <span class="text-red-400">*</span></label>
                                <input type="text" id="contact_name" name="name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-dark-surface text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition"
                                    placeholder="Your full name">
                            </div>
                            <div>
                                <label for="contact_email" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email Address <span class="text-red-400">*</span></label>
                                <input type="email" id="contact_email" name="email" value="{{ old('email') }}" required
                                    class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-dark-surface text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition"
                                    placeholder="your@email.com">
                            </div>
                        </div>

                        <div>
                            <label for="contact_subject" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Subject <span class="text-red-400">*</span></label>
                            <input type="text" id="contact_subject" name="subject" value="{{ old('subject') }}" required
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-dark-surface text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition"
                                placeholder="Brief subject of your inquiry">
                        </div>

                        <div>
                            <label for="contact_message" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Message <span class="text-red-400">*</span></label>
                            <textarea id="contact_message" name="message" rows="6" required
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-dark-surface text-gray-800 dark:text-gray-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition resize-none"
                                placeholder="Please describe your inquiry in detail...">{{ old('message') }}</textarea>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <p class="text-xs text-gray-400">Fields marked <span class="text-red-400">*</span> are required.</p>
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-base">send</span>
                                Send Message
                            </button>
                        </div>
                    </form>
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
