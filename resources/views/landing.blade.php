@extends('layouts.app')

@section('content')

{{-- ============================================= --}}
{{-- SECTION 1: Hero Banner --}}
{{-- ============================================= --}}
<section id="hero" class="relative min-h-[90vh] flex items-center overflow-hidden" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover; background-position: center;">
    <div class="hero-gradient absolute inset-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 w-full">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-accent/20 border border-accent/30 text-accent px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                <span class="material-symbols-outlined text-base">verified</span>
                {{ __('landing.hero_badge') }}
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                {{ __('landing.hero_title_1') }}<br>
                <span class="text-accent">{{ __('landing.hero_title_2') }}</span>
            </h1>

            <p class="text-lg sm:text-xl text-white/80 leading-relaxed mb-10 max-w-2xl">
                {{ __('landing.hero_subtitle') }}
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="#about" class="inline-flex items-center gap-2 bg-accent hover:bg-accent-500 text-primary font-semibold px-7 py-3.5 rounded-xl text-base transition-colors shadow-lg shadow-accent/25">
                    <span class="material-symbols-outlined">flag</span>
                    {{ __('landing.hero_mission_btn') }}
                </a>
                <a href="#news" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-7 py-3.5 rounded-xl text-base transition-colors backdrop-blur-sm">
                    <span class="material-symbols-outlined">article</span>
                    {{ __('landing.hero_reports_btn') }}
                </a>
            </div>
        </div>
    </div>

</section>

{{-- ============================================= --}}
{{-- SECTION 2: Statistics Bar --}}
{{-- ============================================= --}}
<section class="relative z-10 -mt-8 mb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl shadow-black/5 dark:shadow-black/30 p-6 sm:p-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @php
                    $stats = [
                        ['value' => '11', 'label' => __('landing.stats_members'), 'icon' => 'public'],
                        ['value' => '6', 'label' => __('landing.stats_partners'), 'icon' => 'handshake'],
                        ['value' => '44+', 'label' => __('landing.stats_conferences'), 'icon' => 'groups'],
                        ['value' => '1981', 'label' => __('landing.stats_established'), 'icon' => 'calendar_month'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/5 dark:bg-primary/20 mb-3">
                            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $stat['icon'] }}</span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $stat['value'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 3: Latest News --}}
{{-- ============================================= --}}
<section id="news" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">newspaper</span>
                {{ __('landing.news_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.news_title') }}</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestNews as $item)
                <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}"
                   class="relative rounded-xl overflow-hidden shadow-sm dark:shadow-black/20 border border-gray-100 dark:border-gray-700/50 group h-72 block">
                    {{-- Background: real thumbnail or gradient fallback --}}
                    @if($item->thumbnail)
                    <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    @else
                    <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-400 to-accent/40 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white/20 text-8xl">newspaper</span>
                    </div>
                    @endif

                    {{-- Date badge --}}
                    <div class="absolute top-3 left-3 z-10 group-hover:opacity-0 transition-opacity duration-300">
                        <span class="bg-accent text-primary text-xs font-bold px-3 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Default: title at bottom --}}
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 via-black/40 to-transparent z-10 group-hover:opacity-0 transition-opacity duration-300">
                        <h3 class="font-bold text-white text-sm leading-snug line-clamp-2">{{ $item->title }}</h3>
                    </div>

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 z-20 bg-primary/92 dark:bg-primary-900/97 flex flex-col justify-start p-5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
                        <div class="flex items-center gap-1.5 text-xs text-white/60 mb-1.5">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                        </div>
                        <h3 class="font-bold text-white text-sm leading-snug mb-2">{{ $item->title }}</h3>
                        @if($item->summary)
                        <p class="text-white/75 text-xs leading-relaxed mb-3 line-clamp-4">{{ $item->summary }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1 text-accent font-semibold text-sm mt-auto">
                            {{ __('landing.news_read_more') }}
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center gap-2 border border-primary text-primary hover:bg-primary hover:text-white font-semibold px-6 py-3 rounded-xl transition-colors dark:border-accent dark:text-accent dark:hover:bg-accent dark:hover:text-primary">
                <span class="material-symbols-outlined">newspaper</span>
                View All News
            </a>
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 4: Upcoming Events --}}
{{-- ============================================= --}}
<section id="events" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">event</span>
                {{ __('landing.upcoming_events') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.upcoming_events') }}</h2>
        </div>

        @php
            $events = [
                ['month' => 'SEP', 'day' => '18', 'year' => '2013', 'title' => __('landing.event_1_title'), 'location' => __('landing.event_1_location')],
                ['month' => 'AUG', 'day' => '01', 'year' => '2013', 'title' => __('landing.event_2_title'), 'location' => __('landing.event_2_location')],
                ['month' => 'FEB', 'day' => '19', 'year' => '2013', 'title' => __('landing.event_3_title'), 'location' => __('landing.event_3_location')],
            ];
        @endphp
        @php $loc = ['locale' => app()->getLocale()]; @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($events as $event)
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-black/20 flex gap-5 items-start card-hover">
                    <div class="flex-shrink-0 w-16 h-16 bg-primary rounded-xl flex flex-col items-center justify-center text-white shadow-lg shadow-primary/30">
                        <span class="text-[10px] font-bold uppercase tracking-wider leading-none">{{ $event['month'] }}</span>
                        <span class="text-2xl font-extrabold leading-none mt-0.5">{{ $event['day'] }}</span>
                        <span class="text-[9px] text-white/60 leading-none mt-0.5">{{ $event['year'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary dark:text-white text-base leading-snug mb-2">{{ $event['title'] }}</h3>
                        <p class="text-sm text-gray-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                            {{ $event['location'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('events-training.calendar', $loc) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">calendar_month</span>
                View All Events
            </a>
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 5: About ASEANAPOL --}}
{{-- ============================================= --}}
<section id="about" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">info</span>
                {{ __('landing.about_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.about_title') }}</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            {{-- Vision --}}
            <div class="bg-primary/[0.07] dark:bg-primary/20 border border-primary/10 dark:border-primary/30 rounded-2xl p-8 lg:p-10 card-hover">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-primary flex items-center justify-center shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-white text-3xl">visibility</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary dark:text-white">{{ __('landing.vision') }}</h3>
                </div>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                    {!! __('landing.vision_text') !!}
                </p>
                <div class="mt-6 pt-6 border-t border-primary/10 dark:border-primary/30">
                    <p class="text-sm text-primary/70 dark:text-accent/80 italic flex items-center gap-2">
                        <span class="material-symbols-outlined text-accent text-lg">format_quote</span>
                        {{ __('landing.vision_quote') }}
                    </p>
                </div>
            </div>

            {{-- Mission --}}
            <div class="bg-accent/[0.07] dark:bg-accent/10 border border-accent/10 dark:border-accent/20 rounded-2xl p-8 lg:p-10 card-hover">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-accent flex items-center justify-center shadow-lg shadow-accent/30">
                        <span class="material-symbols-outlined text-primary text-3xl">track_changes</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary dark:text-white">{{ __('landing.mission') }}</h3>
                </div>
                <ul class="space-y-4">
                    @foreach([__('landing.mission_1'), __('landing.mission_2'), __('landing.mission_3'), __('landing.mission_4')] as $mission)
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">check_circle</span>
                            <span class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $mission }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 6: Member Countries Grid --}}
{{-- ============================================= --}}
<section id="members" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">public</span>
                {{ __('landing.members_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.members_title') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ __('landing.members_subtitle') }}</p>
        </div>

        @php
            $countries = [
                ['code' => 'bn', 'name' => 'Brunei Darussalam', 'logo' => 'brunei-rbpf.png',       'short' => 'RBPF'],
                ['code' => 'kh', 'name' => 'Cambodia',          'logo' => 'cambodia-cnp.png',       'short' => 'CNP'],
                ['code' => 'id', 'name' => 'Indonesia',         'logo' => 'indonesia-polri.png',    'short' => 'INP'],
                ['code' => 'la', 'name' => 'Lao PDR',           'logo' => 'laos-lpp.png',           'short' => 'LPF'],
                ['code' => 'my', 'name' => 'Malaysia',          'logo' => 'malaysia-pdrm.png',      'short' => 'RMP'],
                ['code' => 'mm', 'name' => 'Myanmar',           'logo' => 'myanmar-mpf.png',        'short' => 'MPF'],
                ['code' => 'ph', 'name' => 'Philippines',       'logo' => 'philippines-pnp.png',    'short' => 'PNP'],
                ['code' => 'sg', 'name' => 'Singapore',         'logo' => 'singapore-spf.png',      'short' => 'SPF'],
                ['code' => 'th', 'name' => 'Thailand',          'logo' => 'thailand-rtp.png',       'short' => 'RTP'],
                ['code' => 'vn', 'name' => 'Viet Nam',          'logo' => 'vietnam-mps.png',        'short' => 'VPF'],
                ['code' => 'tl', 'name' => 'Timor-Leste',       'logo' => 'timorleste-pntl.png',    'short' => 'PNTL'],
            ];
        @endphp

        {{-- Infinite slider: overflow hidden + CSS marquee, duplicated for seamless loop --}}
        <div class="relative overflow-hidden" style="mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);">
            <div class="flex gap-5 members-track" style="width: max-content;">
                {{-- Original set --}}
                @foreach($countries as $country)
                    <div class="bg-white dark:bg-dark-card rounded-xl p-5 text-center shadow-sm dark:shadow-black/20 group cursor-default border border-transparent dark:border-gray-700/50 flex-shrink-0 w-44 hover:-translate-y-1 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto mb-3">
                            <img src="/media/police-logo/{{ $country['logo'] }}"
                                 alt="{{ $country['name'] }} police logo"
                                 class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <div class="flex items-center justify-center gap-1.5 mb-2">
                            <img src="https://flagcdn.com/w40/{{ $country['code'] }}.png"
                                 alt="{{ $country['name'] }}"
                                 class="h-4 w-auto rounded-sm shadow-sm opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-xs font-bold text-accent">{{ $country['short'] }}</span>
                        </div>
                        <h4 class="text-xs font-semibold text-primary dark:text-white leading-tight">{{ $country['name'] }}</h4>
                    </div>
                @endforeach
                {{-- Duplicate set for seamless loop --}}
                @foreach($countries as $country)
                    <div class="bg-white dark:bg-dark-card rounded-xl p-5 text-center shadow-sm dark:shadow-black/20 group cursor-default border border-transparent dark:border-gray-700/50 flex-shrink-0 w-44 hover:-translate-y-1 transition-transform duration-300" aria-hidden="true">
                        <div class="w-16 h-16 mx-auto mb-3">
                            <img src="/media/police-logo/{{ $country['logo'] }}"
                                 alt=""
                                 class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <div class="flex items-center justify-center gap-1.5 mb-2">
                            <img src="https://flagcdn.com/w40/{{ $country['code'] }}.png"
                                 alt=""
                                 class="h-4 w-auto rounded-sm shadow-sm opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-xs font-bold text-accent">{{ $country['short'] }}</span>
                        </div>
                        <h4 class="text-xs font-semibold text-primary dark:text-white leading-tight">{{ $country['name'] }}</h4>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
            .members-track {
                animation: members-scroll 28s linear infinite;
            }
            .members-track:hover {
                animation-play-state: paused;
            }
            @keyframes members-scroll {
                0%   { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
        </style>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 7: Member Directory Table --}}
{{-- ============================================= --}}
<section id="directory" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">contacts</span>
                {{ __('landing.dir_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.dir_title') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ __('landing.dir_subtitle') }}</p>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm dark:shadow-black/20 overflow-hidden border border-gray-100 dark:border-gray-700/50">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-5 py-4 text-left font-semibold">{{ __('landing.dir_country') }}</th>
                            <th class="px-5 py-4 text-left font-semibold">{{ __('landing.dir_organisation') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden md:table-cell">{{ __('landing.dir_telephone') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden lg:table-cell">{{ __('landing.dir_fax') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden sm:table-cell">{{ __('landing.dir_email') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @php
                            $directory = [
                                ['code' => 'bn', 'logo' => 'brunei-rbpf.png',    'country' => 'Brunei Darussalam', 'org' => 'Royal Brunei Police Force (RBPF)', 'tel' => '+673-2459 500', 'fax' => '+673 245 9527', 'email' => 'rbpf.interpol@police.gov.bn'],
                                ['code' => 'kh', 'logo' => 'cambodia-cnp.png',   'country' => 'Cambodia',          'org' => 'Cambodian National Police (CNP)', 'tel' => '+855 23216585', 'fax' => '+855 2321 6585', 'email' => 'camcontactperson@gmail.com'],
                                ['code' => 'id', 'logo' => 'indonesia-polri.png','country' => 'Indonesia',         'org' => 'Indonesia National Police (INP)', 'tel' => '+021 739 3650', 'fax' => '+021 720 1402', 'email' => 'ncb-jakarta@interpol.go.id'],
                                ['code' => 'la', 'logo' => 'laos-lpp.png',       'country' => 'Lao PDR',           'org' => 'Lao Police Force (LPF)', 'tel' => '+85 6213 16323', 'fax' => '+856 2131 6323', 'email' => 'ncbvientiane@gmail.com'],
                                ['code' => 'my', 'logo' => 'malaysia-pdrm.png',  'country' => 'Malaysia',          'org' => 'Royal Malaysia Police (RMP)', 'tel' => '+603 2266 2222', 'fax' => '+603 2070 7500', 'email' => 'rmp@rmp.gov.my'],
                                ['code' => 'mm', 'logo' => 'myanmar-mpf.png',    'country' => 'Myanmar',           'org' => 'Myanmar Police Force (MPF)', 'tel' => '+95 6741 2066', 'fax' => '+95 6741 2188', 'email' => 'naypyitaw.ncb@gmail.com'],
                                ['code' => 'ph', 'logo' => 'philippines-pnp.png','country' => 'Philippines',       'org' => 'Philippines National Police (PNP)', 'tel' => '+632 8723 0401', 'fax' => '+632 7218549', 'email' => 'laiad.dpl@pnp.gov.ph'],
                                ['code' => 'sg', 'logo' => 'singapore-spf.png',  'country' => 'Singapore',         'org' => 'Singapore Police Force (SPF)', 'tel' => '1800 358 000', 'fax' => '+65 6256 1296', 'email' => 'www.police.gov.sg/e-services'],
                                ['code' => 'th', 'logo' => 'thailand-rtp.png',   'country' => 'Thailand',          'org' => 'Royal Thai Police (RTP)', 'tel' => '+6622053001', 'fax' => '+6622533856', 'email' => 'aseanapol.th@gmail.com'],
                                ['code' => 'vn', 'logo' => 'vietnam-mps.png',    'country' => 'Viet Nam',          'org' => 'Vietnam Police Force (VPF)', 'tel' => '+8424 3938 7173', 'fax' => '+8424 3938 7176', 'email' => 'division6@dfir.gov.vn'],
                                ['code' => 'tl', 'logo' => 'timorleste-pntl.png','country' => 'Timor-Leste',       'org' => 'National Police of Timor-Leste (PNTL)', 'tel' => '—', 'fax' => '—', 'email' => null],
                            ];
                        @endphp
                        @foreach($directory as $entry)
                            <tr class="hover:bg-primary/[0.02] dark:hover:bg-white/[0.03] transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://flagcdn.com/w40/{{ $entry['code'] }}.png" alt="{{ $entry['country'] }}" class="w-8 h-auto rounded-sm shadow-sm">
                                        <span class="font-medium text-primary dark:text-white">{{ $entry['country'] }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <img src="/media/police-logo/{{ $entry['logo'] }}" alt="" class="w-6 h-6 object-contain flex-shrink-0">
                                        {{ $entry['org'] }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $entry['tel'] }}</td>
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $entry['fax'] }}</td>
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    @if(empty($entry['email']))
                                        <span class="text-gray-400">—</span>
                                    @elseif(str_starts_with($entry['email'], 'www.'))
                                        <a href="https://{{ $entry['email'] }}" target="_blank" rel="noopener" class="text-accent hover:text-accent-700 font-medium transition-colors">{{ $entry['email'] }}</a>
                                    @else
                                        <a href="mailto:{{ $entry['email'] }}" class="text-accent hover:text-accent-700 font-medium transition-colors">{{ $entry['email'] }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="#" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">download</span>
                {{ __('landing.dir_download') }}
            </a>
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 8: Quick Resources --}}
{{-- ============================================= --}}
<section id="resources" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">folder_open</span>
                {{ __('landing.quick_resources') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.quick_resources') }}</h2>
        </div>

        @php
            $resources = [
                ['title' => __('landing.res_eads'), 'icon' => 'database', 'desc' => 'ASEAN Electronic Alert & Data System'],
                ['title' => __('landing.res_annual'), 'icon' => 'description', 'desc' => 'Yearly operational and statistical reports'],
                ['title' => __('landing.res_constitution'), 'icon' => 'gavel', 'desc' => 'Founding charter and governing rules'],
                ['title' => __('landing.res_media'), 'icon' => 'photo_library', 'desc' => 'Photos, videos, and press materials'],
            ];
        @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($resources as $resource)
                <a href="#" class="group bg-white dark:bg-dark-card rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-black/20 flex flex-col items-center text-center card-hover">
                    <div class="w-14 h-14 rounded-xl bg-primary/5 dark:bg-primary/20 flex items-center justify-center mb-4 group-hover:bg-primary group-hover:shadow-lg group-hover:shadow-primary/30 transition-all duration-300">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl group-hover:text-white transition-colors duration-300">{{ $resource['icon'] }}</span>
                    </div>
                    <h3 class="font-bold text-primary dark:text-white text-sm mb-1.5 group-hover:text-accent transition-colors">{{ $resource['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $resource['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================= --}}
{{-- SECTION 9: Partners --}}
{{-- ============================================= --}}
<section id="partners" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">handshake</span>
                {{ __('landing.footer_partners_label') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ __('landing.footer_partners_label') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ __('landing.stats_partners') }}</p>
        </div>

        @php
            $partners = [
                [
                    'name' => 'INTERPOL',
                    'full' => 'International Criminal Police Organization',
                    'icon' => 'policy',
                    'url' => 'https://www.interpol.int',
                    'desc' => 'Global police cooperation organisation enabling law enforcement worldwide to work together to make the world a safer place.',
                ],
                [
                    'name' => 'Europol',
                    'full' => 'European Union Agency for Law Enforcement Cooperation',
                    'icon' => 'shield',
                    'url' => 'https://www.europol.europa.eu',
                    'desc' => 'The EU\'s law enforcement agency supporting EU member states in their fight against terrorism, cybercrime and other serious crime.',
                ],
                [
                    'name' => 'UNODC',
                    'full' => 'United Nations Office on Drugs and Crime',
                    'icon' => 'balance',
                    'url' => 'https://www.unodc.org',
                    'desc' => 'The global leader in the fight against illicit drugs, international crime, corruption, and terrorism.',
                ],
                [
                    'name' => 'ACCBP',
                    'full' => 'ASEAN Chiefs of Customs & Border Police',
                    'icon' => 'security',
                    'url' => '#',
                    'desc' => 'Regional cooperation body focused on customs and border enforcement across ASEAN member states.',
                ],
            ];
        @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($partners as $partner)
                <a href="{{ $partner['url'] }}" {{ $partner['url'] !== '#' ? 'target="_blank" rel="noopener"' : '' }} class="group bg-primary/[0.04] dark:bg-primary/20 border border-primary/10 dark:border-primary/30 rounded-2xl p-6 flex flex-col card-hover">
                    <div class="w-14 h-14 rounded-xl bg-primary flex items-center justify-center mb-5 shadow-lg shadow-primary/30 group-hover:bg-accent transition-colors duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">{{ $partner['icon'] }}</span>
                    </div>
                    <div class="font-extrabold text-xl text-primary dark:text-white mb-1 group-hover:text-accent dark:group-hover:text-accent transition-colors">{{ $partner['name'] }}</div>
                    <div class="text-xs font-semibold text-primary/60 dark:text-gray-400 uppercase tracking-wide mb-3">{{ $partner['full'] }}</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mt-auto">{{ $partner['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
