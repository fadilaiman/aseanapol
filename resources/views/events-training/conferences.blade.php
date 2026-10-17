@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('events.conferences', 'hero.title'),
    'subtitle' => cms('events.conferences', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',                  ['locale' => app()->getLocale()])],
        ['label' => cms('events.index', 'hero.title'), 'url' => route('events-training.index',    ['locale' => app()->getLocale()])],
        ['label' => cms('events.conferences', 'hero.breadcrumb'),       'url' => ''],
    ],
])
@endsection

@section('content')

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(cms_on('events.conferences', 'notice'))
        {{-- COVID notice --}}
        <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl px-5 py-4 flex gap-3 items-start text-sm text-amber-800 dark:text-amber-300">
            <span class="material-symbols-outlined text-xl flex-shrink-0 mt-0.5">{{ cms('events.conferences', 'notice.icon') }}</span>
            <span>{{ cms_html('events.conferences', 'notice.text') }}</span>
        </div>
        @endif

        {{-- Conference list --}}
        <div class="space-y-4">
            @foreach($conferences as $conf)
            @php
                $loc = $conf->location;
                $isUpcoming = $conf->status === 'upcoming';
                $isSuspended = $conf->status === 'suspended';
            @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border {{ $isUpcoming ? 'border-primary/30 dark:border-primary/40' : ($isSuspended ? 'border-gray-200 dark:border-gray-700 opacity-60' : 'border-gray-100 dark:border-gray-700') }} flex gap-5 items-start">
                {{-- Year badge --}}
                <div class="flex-shrink-0 w-16 text-center">
                    @if($conf->flag)
                    <div class="text-2xl leading-none mb-0.5">{{ $conf->flag }}</div>
                    @else
                    <div class="text-2xl leading-none mb-0.5 text-gray-300 dark:text-gray-600">—</div>
                    @endif
                    <div class="text-lg font-extrabold text-primary dark:text-accent leading-none">{{ $conf->conf_year }}</div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $isUpcoming ? 'bg-primary text-white' : ($isSuspended ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-accent') }}">
                            {{ $conf->conf_number }} {{ cms('events.conferences', 'labels.conference_word') }}
                        </span>
                        @if($isUpcoming)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/20 text-accent uppercase tracking-wider">{{ cms('events.conferences', 'labels.forthcoming') }}</span>
                        @elseif($isSuspended)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400 uppercase tracking-wider">{{ cms('events.conferences', 'labels.suspended') }}</span>
                        @endif
                    </div>
                    @if($isSuspended)
                    <h3 class="font-bold text-gray-400 dark:text-gray-500 text-sm leading-snug">{{ cms('events.conferences', 'labels.suspended_text') }}</h3>
                    @else
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $conf->dates_label }}</h3>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                        {{ $loc }}
                    </p>
                    @if($conf->theme)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">&ldquo;{{ $conf->theme }}&rdquo;</p>
                    @endif
                    @if($conf->link)
                    <a href="{{ $conf->link }}" target="_blank" rel="noopener" class="inline-block mt-2 text-xs font-semibold text-primary dark:text-accent hover:underline">{{ $conf->link_label ?: 'More information' }} →</a>
                    @endif
                    @endif
                </div>
                @if($conf->image_url)
                <img src="{{ $conf->image_url }}" alt="{{ $conf->title }}" class="lightbox-img flex-shrink-0 w-24 h-16 object-cover rounded-lg" loading="lazy">
                @endif
            </div>
            @endforeach
        </div>

        @if(count($conferences) === 0)
        <p class="text-center text-sm text-gray-400 py-10">{{ cms('events.conferences', 'labels.empty') }}</p>
        @endif

        @if(cms_on('events.conferences', 'back'))
        {{-- Back link --}}
        <div class="mt-10">
            <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-primary dark:text-accent hover:underline">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                {{ cms('events.conferences', 'back.label') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
