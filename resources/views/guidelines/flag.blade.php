@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('guidelines.flag', 'hero.title'),
    'subtitle' => cms('guidelines.flag', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                             'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',                       'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => cms('guidelines.flag', 'hero.crumb'),        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                @if(cms_on('guidelines.flag', 'head'))
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">{{ cms('guidelines.flag', 'head.icon') }}</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">{{ cms('guidelines.flag', 'head.kicker') }}</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">{{ cms('guidelines.flag', 'head.heading') }}</h2>
                    </div>
                </div>
                @endif

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        {{ cms('guidelines.flag', 'body.intro') }}
                    </p>

                    @include('guidelines._sections', ['rows' => cms('guidelines.flag', 'body.sections')])
                </div>

                @include('guidelines._document', ['key' => 'guidelines.flag'])

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 items-center justify-between">
                    @if(cms('guidelines.flag', 'foot.email'))
                    <p class="text-sm text-gray-400">{{ cms('guidelines.flag', 'foot.label') }} <a href="mailto:{{ cms('guidelines.flag', 'foot.email') }}" class="text-primary dark:text-accent hover:underline">{{ cms('guidelines.flag', 'foot.email') }}</a></p>
                    @endif
                    <a href="{{ route('guidelines.index', ['locale' => app()->getLocale()]) }}"
                       class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        {{ cms('guidelines.flag', 'foot.back_label') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
