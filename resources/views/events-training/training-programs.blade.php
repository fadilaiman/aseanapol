@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('events.training-programs', 'hero.title'),
    'subtitle' => cms('events.training-programs', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                    ['locale' => app()->getLocale()])],
        ['label' => cms('events.index', 'hero.title'),'url' => route('events-training.index',      ['locale' => app()->getLocale()])],
        ['label' => cms('events.training-programs', 'hero.title'),'url' => ''],
    ],
])
@endsection

@section('content')
@php
$categoryColors = [
    'Governance'     => 'bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent',
    'Cybercrime'     => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
    'TIP'            => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300',
    'Drugs'          => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
    'Financial Crime'=> 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
    'Wildlife'       => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(cms_on('events.training-programs', 'intro'))
        {{-- Intro note --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">{{ cms('events.training-programs', 'intro.icon') }}</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ str_replace('{members}', (string) shared('organisation', 'counts.member_count_word'), cms('events.training-programs', 'intro.before')) }}
                <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}" class="text-primary dark:text-accent font-semibold hover:underline">{{ cms('events.training-programs', 'intro.calendar') }}</a>{{ cms('events.training-programs', 'intro.after_cal') }}
                {{ cms('events.training-programs', 'intro.middle') }}
                @if(shared('secretariat', 'contact.email_general'))<a href="mailto:{{ shared('secretariat', 'contact.email_general') }}" class="text-primary dark:text-accent font-semibold hover:underline">{{ shared('secretariat', 'contact.email_general') }}</a>@endif{{ cms('events.training-programs', 'intro.after_email') }}
            </p>
        </div>
        @endif

        {{-- Program list --}}
        <div class="space-y-5">
            @foreach($programs as $p)
            <article class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row gap-5">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $p->icon }}</span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 dark:text-white leading-snug">{{ $p->title }}</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $categoryColors[$p->category] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }} uppercase tracking-wider">{{ $p->category }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3">{{ $p->description }}</p>
                        <div class="flex flex-wrap gap-4 text-xs text-gray-400 dark:text-gray-500">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">repeat</span>
                                {{ $p->frequency }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">event</span>
                                {{ $p->latest }}
                            </span>
                        </div>
                        @if($p->link)
                        <a href="{{ $p->link }}" target="_blank" rel="noopener" class="inline-block mt-3 text-xs font-semibold text-primary dark:text-accent hover:underline">{{ $p->link_label ?: 'More information' }} →</a>
                        @endif
                    </div>
                    @if($p->image_url)
                    <img src="{{ $p->image_url }}" alt="{{ $p->title }}" class="lightbox-img flex-shrink-0 w-28 h-20 object-cover rounded-lg" loading="lazy">
                    @endif
                </div>
            </article>
            @endforeach
        </div>

        @if(count($programs) === 0)
        <p class="text-center text-sm text-gray-400 py-10">{{ cms('events.training-programs', 'labels.empty') }}</p>
        @endif

        @if(cms_on('events.training-programs', 'cta'))
        {{-- Calendar CTA --}}
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ cms('events.training-programs', 'cta.text') }}
            </p>
            <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                {{ cms('events.training-programs', 'cta.button') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
