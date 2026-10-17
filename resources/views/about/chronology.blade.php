@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.chronology', 'hero.title'),
    'subtitle' => \App\Support\SiteCms::tpl(cms('about.chronology', 'hero.subtitle')),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.chronology', 'hero.title'),      'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(cms_on('about.chronology', 'timeline'))
        <div class="relative">
            {{-- Vertical line --}}
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-primary/20 dark:bg-primary/40 hidden sm:block"></div>

            <div class="space-y-8">
                @foreach(cms('about.chronology', 'timeline.items') as $item)
                <div class="relative flex gap-6 sm:gap-8">
                    {{-- Year dot --}}
                    <div class="hidden sm:flex flex-shrink-0 w-12 h-12 bg-primary dark:bg-accent rounded-full items-center justify-center z-10 mt-1">
                        <span class="material-symbols-outlined text-white dark:text-primary text-xl">{{ cms('about.chronology', 'timeline.dot_icon') }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block bg-primary/10 dark:bg-primary/30 text-primary dark:text-accent font-bold text-sm px-3 py-1 rounded-full">{{ $item['year'] }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ \App\Support\SiteCms::tpl($item['title']) }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ \App\Support\SiteCms::tpl($item['desc']) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(cms_on('about.chronology', 'back'))
        <div class="mt-12 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about.chronology', 'back.label') }}
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
