@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.vision-mission', 'hero.title'),
    'subtitle' => cms('about.vision-mission', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.vision-mission', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Vision --}}
        @if(cms_on('about.vision-mission', 'vision'))
        <div class="bg-primary dark:bg-dark-card rounded-2xl p-8 sm:p-10 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">visibility</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white">{{ cms('about.vision-mission', 'vision.heading') }}</h2>
                </div>
                <blockquote class="text-xl sm:text-2xl font-light text-white/90 leading-relaxed italic border-l-4 border-accent pl-6 mb-6">
                    {{ cms('about.vision-mission', 'vision.quote') }}
                </blockquote>
                <p class="text-accent font-semibold text-sm tracking-wider uppercase">"{{ \App\Support\Shared::org('identity.tagline') }}"</p>
            </div>
        </div>

        @endif

        {{-- Mission --}}
        @if(cms_on('about.vision-mission', 'mission'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 sm:p-10 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">target</span>
                </div>
                <h2 class="text-2xl font-bold text-primary dark:text-white">{{ cms('about.vision-mission', 'mission.heading') }}</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach(cms('about.vision-mission', 'mission.items') as $mission)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-11 h-11 bg-primary/8 dark:bg-primary/25 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $mission['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $mission['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $mission['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @endif

        @if(cms_on('about.vision-mission', 'back'))
        <div class="text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about.vision-mission', 'back.label') }}
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
