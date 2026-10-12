@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('training.in-person-training', 'hero.title'),
    'subtitle' => cms('training.in-person-training', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',               'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => cms('training.index', 'hero.title'),           'url' => route('training.index', ['locale' => app()->getLocale()])],
        ['label' => cms('training.in-person-training', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        @if(cms_on('training.in-person-training', 'soon'))
        <div class="w-24 h-24 rounded-3xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-primary dark:text-accent" style="font-size: 48px;">{{ cms('training.in-person-training', 'soon.icon') }}</span>
        </div>

        <h2 class="text-4xl sm:text-5xl font-bold text-primary dark:text-white mb-4">{{ cms('training.in-person-training', 'soon.heading') }}</h2>
        <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
            {{ cms_html('training.in-person-training', 'soon.body') }}
        </p>
        @endif

    </div>
</section>
@endsection
