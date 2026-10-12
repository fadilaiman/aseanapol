@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('careers.vacancies', 'hero.title'),
    'subtitle' => cms('careers.vacancies', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => cms('careers.vacancies', 'hero.parent_label'), 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => cms('careers.vacancies', 'hero.title'),        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Current openings (shown only when the admin has added visible rows) --}}
        @include('careers._openings', ['key' => 'careers.vacancies'])

        {{-- No active openings notice --}}
        @if(cms_on('careers.vacancies', 'notice'))
        <div @if(count(cms('careers.vacancies', 'openings.items'))) hidden aria-hidden="true" @endif class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center mb-10">
            <div class="w-16 h-16 rounded-2xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl">{{ cms('careers.vacancies', 'notice.icon') }}</span>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ cms('careers.vacancies', 'notice.heading') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                {{ cms('careers.vacancies', 'notice.text') }}
            </p>
        </div>
        @endif

        {{-- Position types --}}
        @if(cms_on('careers.vacancies', 'types'))
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">{{ cms('careers.vacancies', 'types.heading') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
            @foreach(cms('careers.vacancies', 'types.items') as $t)
            <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $t['icon'] }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $t['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $t['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Stay updated --}}
        @if(cms_on('careers.vacancies', 'updates'))
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6 flex items-start gap-4">
            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0">{{ cms('careers.vacancies', 'updates.icon') }}</span>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ cms('careers.vacancies', 'updates.heading') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ cms('careers.vacancies', 'updates.text_before') }}
                    <a href="mailto:{{ cms('careers.vacancies', 'updates.email') }}" class="text-primary dark:text-accent font-semibold hover:underline">{{ cms('careers.vacancies', 'updates.email') }}</a>{{ cms('careers.vacancies', 'updates.text_after') }}
                </p>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
