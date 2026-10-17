@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('guidelines.index', 'hero.title'),
    'subtitle' => cms('guidelines.index', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',       'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('guidelines.index', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(cms_on('guidelines.index', 'intro'))
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('guidelines.index', 'intro.text') }}
            </p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
            @foreach(cms('guidelines.index', 'cards.items') as $g)
            <a href="{{ !empty($g['custom_url']) ? $g['custom_url'] : (!empty($g['target']) ? route($g['target'], ['locale' => app()->getLocale()]) : '#') }}"
               class="card-hover group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all flex flex-col">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center group-hover:bg-primary dark:group-hover:bg-accent transition-all">
                        <span class="material-symbols-outlined text-primary dark:text-accent group-hover:text-white dark:group-hover:text-primary text-2xl transition-colors">{{ $g['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-primary dark:group-hover:text-accent transition-colors leading-snug">{{ $g['title'] }}</h3>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed flex-1">{{ $g['desc'] }}</p>
                <div class="flex items-center gap-1 mt-5 text-primary dark:text-accent text-sm font-medium">
                    <span class="material-symbols-outlined text-base">{{ cms('guidelines.index', 'cards.read_icon') }}</span>
                    {{ cms('guidelines.index', 'cards.read_label') }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
