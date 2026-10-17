@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('members.index', 'hero.title'),
    'subtitle' => \App\Support\MemberPages::fill((string) cms('members.index', 'hero.subtitle')),
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',   'url' => route('about.index',         ['locale' => app()->getLocale()])],
        ['label' => cms('members.index', 'hero.title'),  'url' => ''],
    ],
])
@endsection

@section('content')

{{-- Member Countries --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-3">
                <span class="material-symbols-outlined text-base">public</span>
                {{ cms('members.index', 'intro.badge') }}
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-primary dark:text-white">{{ \App\Support\MemberPages::fill((string) cms('members.index', 'intro.heading')) }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">{{ \App\Support\MemberPages::fill((string) cms('members.index', 'intro.text')) }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($members as $m)
            <a href="{{ route($m['route'], ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center hover:border-primary/20 dark:hover:border-accent/30 transition-all relative">
                @if(!empty($m['badge']))
                <span class="absolute top-3 right-3 text-[10px] font-bold bg-accent text-primary px-2 py-0.5 rounded-full uppercase tracking-wide">{{ $m['badge'] }}</span>
                @endif
                <img src="https://flagcdn.com/w80/{{ $m['iso'] }}.png"
                     alt="{{ $m['country'] }} flag"
                     class="w-16 h-10 object-cover rounded shadow-sm mb-3 group-hover:scale-105 transition-transform">
                <div class="font-semibold text-gray-900 dark:text-white text-sm group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ $m['country'] }}</div>
                <div class="text-gray-400 dark:text-gray-500 text-xs mt-1 leading-tight">{{ $m['name'] }}</div>
                @if(!empty($m['since']))
                <span class="mt-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">{{ cms('members.index', 'card_labels.since') }} {{ $m['since'] }}</span>
                @endif
                <span class="mt-3 text-primary dark:text-accent text-xs font-medium flex items-center gap-1">
                    {{ cms('members.index', 'card_labels.view') }} <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Partners --}}
<section class="py-16 bg-white dark:bg-dark-card border-t border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-3">
                <span class="material-symbols-outlined text-base">handshake</span>
                {{ cms('members.index', 'partners.badge') }}
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-primary dark:text-white">{{ cms('members.index', 'partners.heading') }}</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl">
            <a href="{{ route('about.dialogue-partners', ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-background dark:bg-dark-surface rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl mb-3 block">forum</span>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ cms('members.index', 'partners.dialogue_title') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ cms('members.index', 'partners.dialogue_text') }}</p>
                <div class="flex items-center gap-1 mt-4 text-primary dark:text-accent text-sm font-medium">
                    {{ cms('members.index', 'partners.view') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>

            <a href="{{ route('about.observers', ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-background dark:bg-dark-surface rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl mb-3 block">visibility</span>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ cms('members.index', 'partners.observer_title') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ cms('members.index', 'partners.observer_text') }}</p>
                <div class="flex items-center gap-1 mt-4 text-primary dark:text-accent text-sm font-medium">
                    {{ cms('members.index', 'partners.view') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
