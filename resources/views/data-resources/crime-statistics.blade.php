@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('data.crime-statistics', 'hero.title'),
    'subtitle' => cms('data.crime-statistics', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                    ['locale' => app()->getLocale()])],
        ['label' => cms('data.hub', 'hero.title'), 'url' => route('data-resources.index',       ['locale' => app()->getLocale()])],
        ['label' => cms('data.crime-statistics', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        @if(cms_on('data.crime-statistics', 'notice'))
        <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-3xl">{{ cms('data.crime-statistics', 'notice.icon') }}</span>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ cms('data.crime-statistics', 'notice.heading') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
            {{ cms('data.crime-statistics', 'notice.body') }}
            @if(shared('secretariat', 'contact.email_general'))<a href="mailto:{{ shared('secretariat', 'contact.email_general') }}" class="font-semibold text-primary dark:text-accent underline hover:no-underline">{{ shared('secretariat', 'contact.email_general') }}</a>@endif.
        </p>
        @endif

        @if(cms_on('data.crime-statistics', 'library'))
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            {{ cms('data.crime-statistics', 'library.text') }}
        </p>
        <a href="{{ route('data-resources.digital-library', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-base">library_books</span>
            {{ cms('data.crime-statistics', 'library.button') }}
        </a>
        @endif

    </div>
</section>
@endsection
