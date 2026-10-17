@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('events.calendar', 'hero.title'),
    'subtitle' => cms('events.calendar', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => cms('events.index', 'hero.title'), 'url' => route('events-training.index',  ['locale' => app()->getLocale()])],
        ['label' => cms('events.calendar', 'hero.title'),    'url' => ''],
    ],
])
@endsection

@section('content')
@php
$typeColors = [
    'Conference' => 'bg-primary text-white',
    'Meeting'    => 'bg-blue-600 text-white',
    'Training'   => 'bg-teal-600 text-white',
    'Activity'   => 'bg-accent text-primary',
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(cms_on('events.calendar', 'legend'))
        {{-- Legend --}}
        <div class="flex flex-wrap gap-3 mb-10">
            @foreach($typeColors as $type => $cls)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full {{ $cls }}">
                {{ $type }}
            </span>
            @endforeach
        </div>
        @endif

        {{-- Timeline by year --}}
        @foreach($byYear as $year => $yearEvents)
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-6">
                <h2 class="text-2xl font-extrabold text-primary dark:text-white">{{ $year }}</h2>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-sm text-gray-400">{{ count($yearEvents) }} {{ count($yearEvents) !== 1 ? cms('events.calendar', 'labels.event_many') : cms('events.calendar', 'labels.event_one') }}</span>
            </div>

            <div class="space-y-4">
                @foreach($yearEvents as $event)
                @php
                    $start  = $event->start_date;
                    $end    = $event->end_date ?? $event->start_date;
                    $sameDay = $start->isSameDay($end);
                    $loc    = $event->location;
                @endphp
                <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-5 items-start">
                    {{-- Date badge --}}
                    <div class="flex-shrink-0 w-16 text-center">
                        <div class="text-2xl font-extrabold text-primary dark:text-white leading-none">{{ $start->format('d') }}</div>
                        <div class="text-xs font-semibold uppercase text-accent tracking-wider">{{ $start->format('M') }}</div>
                        @if(!$sameDay)
                        <div class="text-[10px] text-gray-400 mt-1">{{ cms('events.calendar', 'labels.to_word') }} {{ $end->format('d M') }}</div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start gap-2 mb-1">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $typeColors[$event->type] ?? 'bg-gray-200 text-gray-700' }}">
                                {{ $event->type }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $event->title }}</h3>
                        @if($loc)
                        <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                            {{ $loc }}
                        </p>
                        @endif
                        @if($event->link)
                        <a href="{{ $event->link }}" target="_blank" rel="noopener" class="inline-block mt-2 text-xs font-semibold text-primary dark:text-accent hover:underline">{{ $event->link_label ?: 'More information' }} →</a>
                        @endif
                    </div>
                    @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="lightbox-img flex-shrink-0 w-24 h-16 object-cover rounded-lg" loading="lazy">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        @if(count($byYear) === 0)
        <p class="text-center text-sm text-gray-400 py-10">{{ cms('events.calendar', 'labels.empty') }}</p>
        @endif

    </div>
</section>
@endsection
