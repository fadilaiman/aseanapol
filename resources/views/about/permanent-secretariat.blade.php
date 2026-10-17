@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.permanent-secretariat', 'hero.title'),
    'subtitle' => cms('about.permanent-secretariat', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.permanent-secretariat', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
@php
// Shared-fact tokens ({founded_year}, {member_count_word}, ...) in page text; values come from Shared facts > Organisation.
$fx = function ($t) {
    $k = \App\Support\Shared::all('organisation')['key_dates'] ?? [];
    $c = \App\Support\Shared::all('organisation')['counts'] ?? [];
    $fc = explode(',', (string) ($k['first_conference_label'] ?? ''), 2);
    return strtr((string) $t, [
        '{founded_year}' => (string) ($k['founded_year'] ?? ''),
        '{founded_date}' => (string) ($k['founded_date_label'] ?? ''),
        '{first_conference_date}' => trim($fc[0]),
        '{first_conference_place}' => trim($fc[1] ?? ''),
        '{secretariat_established}' => (string) ($k['secretariat_established_label'] ?? ''),
        '{conference_count}' => (string) ($k['conference_count_label'] ?? ''),
        '{member_count_word}' => (string) ($c['member_count_word'] ?? ''),
        '{member_count_num}' => (string) ($c['member_count_num'] ?? ''),
    ]);
};
@endphp
@php
$sharedLogo = \App\Support\Cms::url((string) \App\Support\Shared::org('identity.logo'));
// Office holders from Shared facts (roster), newest first; the force is shown as the member's abbreviation only.
$dirRows = function (string $post) {
    return array_map(function ($p) {
        $m = !empty($p['member_key']) ? \App\Support\Shared::member($p['member_key']) : null;
        $p['abbr'] = $m['abbreviation'] ?? ($p['force'] ?? '');
        return $p;
    }, \App\Support\Shared::roster($post, null, true));
};
// Rich-text output: newline between block tags (and optional padding inside <p>) so default markup stays identical.
$rt = function (string $path, bool $pad = false) use ($fx) {
    $h = $fx((string) cms_html('about.permanent-secretariat', $path));
    $h = preg_replace('#</(p|ul|ol)>(?=<)#', "</$1>\n", $h);
    if ($pad) { $h = str_replace(['<p>', '</p>'], ["<p>\n", "\n</p>"], $h); }
    return new \Illuminate\Support\HtmlString($h);
};
@endphp

{{-- ── Introduction ──────────────────────────────────────────────────── --}}
@if(cms_on('about.permanent-secretariat', 'intro'))
<section class="py-14 bg-white dark:bg-dark-card border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($sharedLogo)
        <img src="{{ $sharedLogo }}" alt="{{ \App\Support\Shared::org('identity.logo_alt') }}"
             class="h-28 w-auto mx-auto mb-6">
        @endif
        <h2 class="text-3xl font-extrabold text-primary dark:text-white mb-2 tracking-tight uppercase">{{ cms('about.permanent-secretariat', 'intro.heading') }}</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-8"></div>

        <div class="text-left space-y-5 text-gray-600 dark:text-gray-300 leading-relaxed">
            <p class="font-semibold text-gray-800 dark:text-white text-lg">{{ cms('about.permanent-secretariat', 'intro.preface') }}</p>
            {{ $rt('intro.body_top') }}
            <ul class="list-none space-y-1 pl-4 border-l-4 border-accent/30">
                @foreach(cms('about.permanent-secretariat', 'intro.milestones') as $m)
                <li><span class="font-semibold text-primary dark:text-accent">{{ $m['year'] }}</span> — {{ $m['text'] }}</li>
                @endforeach
            </ul>
            {{ $rt('intro.body_bottom') }}
        </div>
    </div>
</section>
@endif

{{-- ── Main content (full-width) ─────────────────────────────────────── --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- About --}}
        @if(cms_on('about.permanent-secretariat', 'about'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">{{ cms('about.permanent-secretariat', 'about.heading') }}</h2>
            <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {{ $rt('about.body', true) }}
            </div>
        </div>
        @endif

        {{-- History --}}
        @if(cms_on('about.permanent-secretariat', 'history'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">{{ cms('about.permanent-secretariat', 'history.heading') }}</h2>
            <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {{ $rt('history.intro', true) }}
                <p class="font-semibold text-gray-800 dark:text-white">{{ cms('about.permanent-secretariat', 'history.obj_title') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach(cms('about.permanent-secretariat', 'history.obj_items') as $it)
                    <li>{{ $it }}</li>
                    @endforeach
                </ul>
                <p>{{ cms('about.permanent-secretariat', 'history.wg_text') }}</p>
                <ol class="list-decimal pl-5 space-y-1">
                    @foreach(cms('about.permanent-secretariat', 'history.wg_items') as $it)
                    <li>{{ $it }}</li>
                    @endforeach
                </ol>
                {{ $rt('history.closing', true) }}
            </div>
        </div>
        @endif

        {{-- Functions --}}
        @if(cms_on('about.permanent-secretariat', 'functions'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">{{ cms('about.permanent-secretariat', 'functions.heading') }}</h2>
            <ul class="space-y-3">
                @foreach(cms('about.permanent-secretariat', 'functions.items') as $fn)
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">{{ $fn['icon'] }}</span>
                    <span class="text-gray-600 dark:text-gray-300">{{ $fn['text'] }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(cms_on('about.permanent-secretariat', 'back'))
        <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            {{ cms('about.permanent-secretariat', 'back.label') }}
        </a>
        @endif

    </div>
</section>

{{-- ── Executive Director and Directors ──────────────────────────────── --}}
@if(cms_on('about.permanent-secretariat', 'directors'))
<section class="py-14 bg-white dark:bg-dark-card border-t border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2 text-center uppercase tracking-wide">{{ cms('about.permanent-secretariat', 'directors.heading') }}</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-10"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Director for Police Services --}}
            @if(cms_on('about.permanent-secretariat', 'dir_ps'))
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-primary px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-accent text-center">{{ cms('about.permanent-secretariat', 'dir_ps.heading') }}</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirRows('dps') as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['full_name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['abbr'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Executive Director --}}
            @if(cms_on('about.permanent-secretariat', 'dir_ed'))
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-accent/40 overflow-hidden shadow-md">
                <div class="bg-accent px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary text-center">{{ cms('about.permanent-secretariat', 'dir_ed.heading') }}</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirRows('ed') as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['full_name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['abbr'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Director for Plans and Programmes --}}
            @if(cms_on('about.permanent-secretariat', 'dir_plans'))
            <div class="bg-gray-50 dark:bg-dark-surface rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-primary px-5 py-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-accent text-center">{{ cms('about.permanent-secretariat', 'dir_plans.heading') }}</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($dirRows('dpp') as $d)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <span class="text-lg leading-none mt-0.5">{{ $d['flag'] }}</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">{{ $d['full_name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d['abbr'] }} &middot; {{ $d['term'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
@endif

@endsection
