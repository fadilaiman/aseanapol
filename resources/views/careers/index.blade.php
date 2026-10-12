@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('careers.index', 'hero.title'),
    'subtitle' => cms('careers.index', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',       ['locale' => app()->getLocale()])],
        ['label' => cms('careers.index', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Intro notice --}}
        @if(cms_on('careers.index', 'notice'))
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">{{ cms('careers.index', 'notice.icon') }}</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ cms('careers.index', 'notice.text_before') }}
                <a href="{{ cms('careers.index', 'notice.link_url') ?: route('contact-us', ['locale' => app()->getLocale()]) }}" class="text-primary dark:text-accent hover:underline font-semibold">{{ cms('careers.index', 'notice.link_label') }}</a>{{ cms('careers.index', 'notice.text_after') }}
            </p>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach(cms('careers.index', 'cards.items') as $s)
            <a href="{{ !empty($s['custom_url']) ? $s['custom_url'] : (!empty($s['target']) ? route($s['target'], ['locale' => app()->getLocale()]) : '#') }}"
               class="group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-4 hover:shadow-md hover:border-primary/30 dark:hover:border-primary/40 transition-all">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $s['icon'] }}</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-primary dark:group-hover:text-accent transition-colors">arrow_forward</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                <div class="text-xs font-semibold text-primary dark:text-accent group-hover:underline">
                    {{ $s['label'] }} →
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
