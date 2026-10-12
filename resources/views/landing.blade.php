@extends('layouts.app')

@section('content')
@php
$P = 'site.landing';
$t = fn ($p, $l = null, $d = false) => \App\Support\SiteCms::t($P, $p, $l, $d);
$L = fn ($v) => \App\Support\SiteCms::link($v);
$maxSlides = max(1, (int) (cms($P, 'slider.max_slides') ?: 10));
$newsCount = max(1, (int) (cms($P, 'news.count') ?: 6));
@endphp

{{-- Optional welcome banner (only when a headline is set) --}}
@if(trim((string) cms($P, 'banner.headline')) !== '')
<section class="relative overflow-hidden bg-primary text-white"
         style="padding-top: calc(var(--header-h, 5rem) + 3rem); padding-bottom: 4rem;@if(cms_img($P, 'banner.image')) background-image: url('{{ css_url(cms_img($P, 'banner.image')) }}'); background-size: cover; background-position: center;@endif">
    <div class="absolute inset-0 hero-gradient"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight max-w-3xl">{{ cms($P, 'banner.headline') }}</h1>
        @if(trim((string) cms($P, 'banner.subtitle')) !== '')
        <p class="text-white/80 mt-4 text-lg max-w-2xl leading-relaxed">{{ cms($P, 'banner.subtitle') }}</p>
        @endif
        @if(trim((string) cms($P, 'banner.button_label')) !== '')
        <a href="{{ $L(cms($P, 'banner.button_link')) }}" class="inline-flex items-center gap-2 mt-8 bg-accent hover:bg-accent-600 text-primary font-semibold px-6 py-3 rounded-xl transition-colors">{{ cms($P, 'banner.button_label') }}</a>
        @endif
    </div>
</section>
@endif

{{-- ============================================= --}}
{{-- SECTION 1: Hero News Slider --}}
{{-- ============================================= --}}
@if(cms_on($P, 'slider'))
<section id="hero" class="relative overflow-hidden" style="height: 90vh; min-height: 400px;">

    {{-- Slides --}}
    @foreach($latestNews->take($maxSlides) as $index => $item)
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
        @if($item->thumbnail)
        {{-- Blurred full-bleed backdrop so no empty space ever shows --}}
        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-2xl opacity-70"
             style="background-image: url('{{ css_url(asset($item->thumbnail)) }}');"></div>
        {{-- Full image, always shown uncropped --}}
        <img src="{{ asset($item->thumbnail) }}"
             alt="{{ $item->title }}"
             class="absolute inset-0 w-full h-full object-contain">
        @else
        <div class="w-full h-full bg-gradient-to-br from-primary via-primary-400 to-accent/40 flex items-center justify-center">
            <span class="material-symbols-outlined text-white/10" style="font-size: 12rem;">newspaper</span>
        </div>
        @endif

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>

        {{-- Caption bar --}}
        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $item->slug]) }}"
           class="absolute bottom-0 left-0 right-0 p-8 z-10 group">
            <div class="max-w-4xl">
                <span class="inline-block bg-accent text-primary text-xs font-bold px-3 py-1 rounded-full mb-3">
                    {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                </span>
                <h2 class="text-white text-xl sm:text-2xl lg:text-3xl font-bold leading-snug group-hover:text-accent/90 transition-colors">
                    {{ $item->title }}
                </h2>
            </div>
        </a>
    </div>
    @endforeach

    {{-- Prev / Next arrows --}}
    <button id="heroPrev"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/60 text-white rounded-full p-3 transition-colors focus:outline-none"
            aria-label="Previous slide">
        <span class="material-symbols-outlined text-3xl">chevron_left</span>
    </button>
    <button id="heroNext"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/60 text-white rounded-full p-3 transition-colors focus:outline-none"
            aria-label="Next slide">
        <span class="material-symbols-outlined text-3xl">chevron_right</span>
    </button>

    {{-- Dot indicators --}}
    <div class="absolute bottom-5 right-8 z-20 flex items-center gap-2">
        @foreach($latestNews->take($maxSlides) as $index => $item)
        <button class="hero-dot rounded-full transition-all duration-300 focus:outline-none {{ $index === 0 ? 'bg-white w-6 h-2' : 'bg-white/50 w-2 h-2' }}"
                aria-label="Go to slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    {{-- Slide counter --}}
    <div class="absolute top-6 right-8 z-20 bg-black/30 text-white text-xs font-semibold px-3 py-1 rounded-full backdrop-blur-sm">
        <span id="heroCurrentSlide">1</span> / {{ $latestNews->take($maxSlides)->count() }}
    </div>

</section>

<script>
(function () {
    var slides   = document.querySelectorAll('.hero-slide');
    var dots     = document.querySelectorAll('.hero-dot');
    var counter  = document.getElementById('heroCurrentSlide');
    var current  = 0;
    var timer;

    function goTo(n) {
        slides[current].classList.remove('opacity-100', 'z-10');
        slides[current].classList.add('opacity-0', 'z-0');
        dots[current].classList.remove('bg-white', 'w-6');
        dots[current].classList.add('bg-white/50', 'w-2');

        current = ((n % slides.length) + slides.length) % slides.length;

        slides[current].classList.remove('opacity-0', 'z-0');
        slides[current].classList.add('opacity-100', 'z-10');
        dots[current].classList.remove('bg-white/50', 'w-2');
        dots[current].classList.add('bg-white', 'w-6');

        if (counter) counter.textContent = current + 1;
    }

    function startTimer() {
        timer = setInterval(function () { goTo(current + 1); }, 5000);
    }

    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    document.getElementById('heroPrev').addEventListener('click', function () { goTo(current - 1); resetTimer(); });
    document.getElementById('heroNext').addEventListener('click', function () { goTo(current + 1); resetTimer(); });

    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goTo(i); resetTimer(); });
    });

    startTimer();
})();
</script>
@endif

{{-- ============================================= --}}
{{-- SECTION 2: Statistics Bar --}}
{{-- ============================================= --}}
@if(cms_on($P, 'stats'))
<section class="relative z-10 -mt-8 mb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl shadow-black/5 dark:shadow-black/30 p-6 sm:p-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @php
                    $stats = \App\Support\SiteCms::rows($P, 'stats.items', ['label' => ['landing.stats_members', 'landing.stats_partners', 'landing.stats_conferences', 'landing.stats_established']]);
                @endphp
                @php
                    // counts/dates come from Shared facts; "custom" rows (and rows saved before this existed) keep their typed value
                    $statValue = fn ($stat) => match ($stat['kind'] ?? 'custom') {
                        'members'     => (string) \App\Support\SiteCms::memberCount(),
                        'dialogue'    => (string) count(\App\Support\Shared::dialogue('dialogue')),
                        'conferences' => (string) \App\Support\Shared::org('key_dates.conference_count_label'),
                        'founded'     => (string) \App\Support\Shared::org('key_dates.founded_year'),
                        default       => (string) ($stat['value'] ?? ''),
                    };
                @endphp
                @foreach($stats as $stat)
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/5 dark:bg-primary/20 mb-3">
                            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $stat['icon'] }}</span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $statValue($stat) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================= --}}
{{-- SECTION 3: Latest News --}}
{{-- ============================================= --}}
@if(cms_on($P, 'news'))
<section id="news" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">newspaper</span>
                {{ $t('news.badge', 'landing.news_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('news.title', 'landing.news_title') }}</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestNews->take($newsCount) as $item)
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

                    {{-- Upcoming event badge --}}
                    @if($item->is_upcoming_event)
                    <div class="absolute top-3 right-3 z-10 group-hover:opacity-0 transition-opacity duration-300">
                        <span class="bg-white text-primary text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">event_upcoming</span>
                            {{ $t('news.upcoming_badge', 'landing.upcoming_event_badge') }}
                        </span>
                    </div>
                    @endif

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
                            {{ $t('news.read_more', 'landing.news_read_more') }}
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center gap-2 border border-primary text-primary hover:bg-primary hover:text-white font-semibold px-6 py-3 rounded-xl transition-colors dark:border-accent dark:text-accent dark:hover:bg-accent dark:hover:text-primary">
                <span class="material-symbols-outlined">newspaper</span>
                {{ cms($P, 'news.view_all') }}
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================= --}}
{{-- SECTION 4: Upcoming Events --}}
{{-- ============================================= --}}
@if(cms_on($P, 'events'))
<section id="events" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">event</span>
                {{ $t('events.badge', 'landing.upcoming_events') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('events.title', 'landing.upcoming_events') }}</h2>
        </div>

        @php
            $events = \App\Support\SiteCms::rows($P, 'events.items', [
                'title'    => array_map(fn ($i) => "landing.event_{$i}_title", range(1, 7)),
                'location' => array_map(fn ($i) => "landing.event_{$i}_location", range(1, 7)),
            ]);
        @endphp
        @php $loc = ['locale' => app()->getLocale()]; @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($events as $event)
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-black/20 flex gap-5 items-start card-hover">
                    <div class="flex-shrink-0 w-16 bg-primary rounded-xl flex flex-col items-center justify-center text-white shadow-lg shadow-primary/30 py-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider leading-none">{{ $event['month'] }}</span>
                        <span class="text-2xl font-extrabold leading-none mt-0.5">{{ $event['day'] }}</span>
                        @if($event['end_day'])
                        <span class="text-[9px] text-white/70 leading-none mt-1">{{ cms($P, 'events.range_word') }} {{ $event['end_day'] }} {{ $event['end_month'] }}</span>
                        @else
                        <span class="text-[9px] text-white/60 leading-none mt-0.5">{{ $event['year'] }}</span>
                        @endif
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
            <a href="{{ $L(cms($P, 'events.view_all_link')) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">calendar_month</span>
                {{ cms($P, 'events.view_all') }}
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================= --}}
{{-- SECTION 5: About ASEANAPOL --}}
{{-- ============================================= --}}
@if(cms_on($P, 'about'))
<section id="about" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">info</span>
                {{ $t('about.badge', 'landing.about_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('about.title', 'landing.about_title') }}</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            {{-- Vision --}}
            <div class="bg-primary/[0.07] dark:bg-primary/20 border border-primary/10 dark:border-primary/30 rounded-2xl p-8 lg:p-10 card-hover">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-primary flex items-center justify-center shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-white text-3xl">visibility</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary dark:text-white">{{ $t('about.vision_heading', 'landing.vision') }}</h3>
                </div>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                    @if(\App\Support\SiteCms::isDefault($P, ['about.vision_before', 'about.vision_strong', 'about.vision_after']) && app()->getLocale() !== 'en')
                    {{-- lang-file text may be machine-translated: escape everything, then restore only the one known highlight tag --}}
                    {!! str_replace(['&lt;strong class=&quot;text-primary dark:text-accent&quot;&gt;', '&lt;/strong&gt;'], ['<strong class="text-primary dark:text-accent">', '</strong>'], e(__('landing.vision_text'))) !!}
                    @else
                    {{ cms($P, 'about.vision_before') }} <strong class="text-primary dark:text-accent">{{ cms($P, 'about.vision_strong') }}</strong> {{ cms($P, 'about.vision_after') }}
                    @endif
                </p>
                <div class="mt-6 pt-6 border-t border-primary/10 dark:border-primary/30">
                    <p class="text-sm text-primary/70 dark:text-accent/80 italic flex items-center gap-2">
                        <span class="material-symbols-outlined text-accent text-lg">format_quote</span>
                        {{ (app()->getLocale() !== 'en' && \App\Support\Shared::isDefault('organisation', 'identity.tagline')) ? __('landing.vision_quote') : '"' . \App\Support\Shared::org('identity.tagline') . '"' }}
                    </p>
                </div>
            </div>

            {{-- Mission --}}
            <div class="bg-accent/[0.07] dark:bg-accent/10 border border-accent/10 dark:border-accent/20 rounded-2xl p-8 lg:p-10 card-hover">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-accent flex items-center justify-center shadow-lg shadow-accent/30">
                        <span class="material-symbols-outlined text-primary text-3xl">track_changes</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary dark:text-white">{{ $t('about.mission_heading', 'landing.mission') }}</h3>
                </div>
                <ul class="space-y-4">
                    @foreach((app()->getLocale() !== 'en' && \App\Support\SiteCms::isDefault($P, ['about.missions'])) ? [__('landing.mission_1'), __('landing.mission_2'), __('landing.mission_3'), __('landing.mission_4')] : cms($P, 'about.missions') as $mission)
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
@endif

{{-- ============================================= --}}
{{-- SECTION 6: Member Countries Grid --}}
{{-- ============================================= --}}
@if(cms_on($P, 'members'))
<section id="members" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">public</span>
                {{ $t('members.badge', 'landing.members_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('members.title', 'landing.members_title') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ \App\Support\SiteCms::tpl($t('members.subtitle', 'landing.members_subtitle')) }}</p>
        </div>

        @php
            // from Shared facts -> Member countries (hidden members already removed)
            $countries = array_map(fn ($m) => [
                'code' => $m['iso'], 'name' => $m['country'], 'logo' => $m['landing_logo'], 'short' => $m['abbreviation'], 'since' => $m['joined_year'],
            ], \App\Support\Shared::members());
        @endphp

        {{-- Infinite slider: overflow hidden + CSS marquee, duplicated for seamless loop --}}
        <div class="relative overflow-hidden" style="mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);">
            <div class="flex gap-5 members-track" style="width: max-content;">
                {{-- Original set --}}
                @foreach($countries as $country)
                    <div class="bg-white dark:bg-dark-card rounded-xl p-5 text-center shadow-sm dark:shadow-black/20 group cursor-default border border-transparent dark:border-gray-700/50 flex-shrink-0 w-44 hover:-translate-y-1 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto mb-3">
                            <img src="{{ \App\Support\SiteCms::logo('/media/police-logo/', $country['logo']) }}"
                                 alt="{{ $country['name'] }} police logo"
                                 class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <span class="text-xs font-bold text-accent block mb-1">{{ $country['short'] }}</span>
                        <div class="flex items-center justify-center gap-1.5">
                            <img src="https://flagcdn.com/w40/{{ $country['code'] }}.png"
                                 alt="{{ $country['name'] }}"
                                 class="h-4 w-auto rounded-sm shadow-sm opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                            <h4 class="text-xs font-semibold text-primary dark:text-white leading-tight">{{ $country['name'] }}</h4>
                        </div>
                        @if(!empty($country['since']))
                        <span class="mt-2 inline-block text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">{{ cms($P, 'members.since_word') }} {{ $country['since'] }}</span>
                        @endif
                    </div>
                @endforeach
                {{-- Duplicate set for seamless loop --}}
                @foreach($countries as $country)
                    <div class="bg-white dark:bg-dark-card rounded-xl p-5 text-center shadow-sm dark:shadow-black/20 group cursor-default border border-transparent dark:border-gray-700/50 flex-shrink-0 w-44 hover:-translate-y-1 transition-transform duration-300" aria-hidden="true">
                        <div class="w-16 h-16 mx-auto mb-3">
                            <img src="{{ \App\Support\SiteCms::logo('/media/police-logo/', $country['logo']) }}"
                                 alt=""
                                 class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <span class="text-xs font-bold text-accent block mb-1">{{ $country['short'] }}</span>
                        <div class="flex items-center justify-center gap-1.5">
                            <img src="https://flagcdn.com/w40/{{ $country['code'] }}.png"
                                 alt=""
                                 class="h-4 w-auto rounded-sm shadow-sm opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                            <h4 class="text-xs font-semibold text-primary dark:text-white leading-tight">{{ $country['name'] }}</h4>
                        </div>
                        @if(!empty($country['since']))
                        <span class="mt-2 inline-block text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">{{ cms($P, 'members.since_word') }} {{ $country['since'] }}</span>
                        @endif
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
@endif

{{-- ============================================= --}}
{{-- SECTION 7: Member Directory Table --}}
{{-- ============================================= --}}
@if(cms_on($P, 'directory'))
<section id="directory" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">contacts</span>
                {{ $t('directory.badge', 'landing.dir_badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('directory.title', 'landing.dir_title') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ $t('directory.subtitle', 'landing.dir_subtitle') }}</p>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm dark:shadow-black/20 overflow-hidden border border-gray-100 dark:border-gray-700/50">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-5 py-4 text-left font-semibold">{{ $t('directory.col_country', 'landing.dir_country') }}</th>
                            <th class="px-5 py-4 text-left font-semibold">{{ $t('directory.col_org', 'landing.dir_organisation') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden md:table-cell">{{ $t('directory.col_tel', 'landing.dir_telephone') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden lg:table-cell">{{ $t('directory.col_fax', 'landing.dir_fax') }}</th>
                            <th class="px-5 py-4 text-left font-semibold hidden sm:table-cell">{{ $t('directory.col_email', 'landing.dir_email') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @php
                            // from Shared facts -> Member countries; a force without a number shows a dash, as before
                            $directory = array_map(fn ($m) => [
                                'code' => $m['iso'], 'logo' => $m['landing_logo'], 'country' => $m['country'], 'org' => $m['name'],
                                'tel' => trim((string) $m['tel']) !== '' ? $m['tel'] : '—', 'fax' => trim((string) $m['fax']) !== '' ? $m['fax'] : '—',
                                'email' => $m['ncb_email'],
                            ], \App\Support\Shared::members());
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
                                        <img src="{{ \App\Support\SiteCms::logo('/media/police-logo/', $entry['logo']) }}" alt="" class="w-6 h-6 object-contain flex-shrink-0">
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

    </div>
</section>
@endif

{{-- ============================================= --}}
{{-- SECTION 8: Quick Resources --}}
{{-- ============================================= --}}
@if(cms_on($P, 'resources'))
<section id="resources" class="py-20 bg-background dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">folder_open</span>
                {{ $t('resources.badge', 'landing.quick_resources') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('resources.title', 'landing.quick_resources') }}</h2>
        </div>

        @php
            $resources = \App\Support\SiteCms::rows($P, 'resources.items', ['title' => ['landing.res_eads', 'landing.res_annual', 'landing.res_constitution', 'landing.res_media']]);
        @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($resources as $resource)
                <a href="{{ $L($resource['link'] ?? '') }}" class="group bg-white dark:bg-dark-card rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-black/20 flex flex-col items-center text-center card-hover">
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
@endif

{{-- ============================================= --}}
{{-- SECTION 9: Partners --}}
{{-- ============================================= --}}
@if(cms_on($P, 'partners'))
<section id="partners" class="py-20 bg-white dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-primary/5 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                <span class="material-symbols-outlined text-base">handshake</span>
                {{ $t('partners.badge', 'landing.footer_partners_label') }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary dark:text-white">{{ $t('partners.title', 'landing.footer_partners_label') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">{{ $t('partners.subtitle', 'landing.stats_partners') }}</p>
        </div>

        @php
            // name / full name / icon / website come from Shared facts -> Partner organisations; descriptions stay local
            $partners = \App\Support\SiteCms::partnerRows(cms($P, 'partners.items') ?? []);
        @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($partners as $partner)
                <a href="{{ $L($partner['url'] ?? '') }}" {{ $L($partner['url'] ?? '') !== '#' ? 'target="_blank" rel="noopener"' : '' }} class="group bg-primary/[0.04] dark:bg-primary/20 border border-primary/10 dark:border-primary/30 rounded-2xl p-6 flex flex-col card-hover">
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
@endif

@endsection
