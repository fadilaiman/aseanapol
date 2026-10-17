@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('guidelines.observers-dialogue', 'hero.title'),
    'subtitle' => cms('guidelines.observers-dialogue', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                         'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',                   'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => cms('guidelines.observers-dialogue', 'hero.crumb'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                @if(cms_on('guidelines.observers-dialogue', 'head'))
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">{{ cms('guidelines.observers-dialogue', 'head.icon') }}</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">{{ cms('guidelines.observers-dialogue', 'head.kicker') }}</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">{{ cms('guidelines.observers-dialogue', 'head.heading') }}</h2>
                    </div>
                </div>
                @endif

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        {{ cms('guidelines.observers-dialogue', 'intro.text') }}
                    </p>

                    {{-- Distinction between Observer and Dialogue Partner --}}
                    @if(cms_on('guidelines.observers-dialogue', 'status'))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-primary/5 dark:bg-primary/15 rounded-xl p-5 border border-primary/10 dark:border-primary/30">
                            <h4 class="font-bold text-primary dark:text-accent mb-2">{{ cms('guidelines.observers-dialogue', 'status.observer_title') }}</h4>
                            <p class="text-sm">{{ cms('guidelines.observers-dialogue', 'status.observer_text') }}</p>
                        </div>
                        <div class="bg-accent/5 dark:bg-accent/10 rounded-xl p-5 border border-accent/15 dark:border-accent/25">
                            <h4 class="font-bold text-accent mb-2">{{ cms('guidelines.observers-dialogue', 'status.dialogue_title') }}</h4>
                            <p class="text-sm">{{ cms('guidelines.observers-dialogue', 'status.dialogue_text') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(cms_on('guidelines.observers-dialogue', 'eligibility'))
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">{{ cms('guidelines.observers-dialogue', 'eligibility.heading') }}</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            @foreach(cms('guidelines.observers-dialogue', 'eligibility.items') as $c)
                            <li>{{ $c['text'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(cms_on('guidelines.observers-dialogue', 'process'))
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">{{ cms('guidelines.observers-dialogue', 'process.heading') }}</h3>
                        <ol class="space-y-3">
                            @foreach(cms('guidelines.observers-dialogue', 'process.steps') as $i => $step)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                                <p>{{ $step['text'] }}</p>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    @if(cms_on('guidelines.observers-dialogue', 'review'))
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">{{ cms('guidelines.observers-dialogue', 'review.heading') }}</h3>
                        <p>{{ cms('guidelines.observers-dialogue', 'review.text') }}</p>
                    </div>
                    @endif

                    @include('guidelines._sections', ['rows' => cms('guidelines.observers-dialogue', 'extra.sections')])
                </div>

                @include('guidelines._document', ['key' => 'guidelines.observers-dialogue'])

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 items-center justify-between">
                    @if(shared('secretariat', 'contact.email_secretariat'))
                    <p class="text-sm text-gray-400">{{ cms('guidelines.observers-dialogue', 'foot.label') }} @if(shared('secretariat', 'contact.email_secretariat'))<a href="mailto:{{ shared('secretariat', 'contact.email_secretariat') }}" class="text-primary dark:text-accent hover:underline">{{ shared('secretariat', 'contact.email_secretariat') }}</a>@endif</p>
                    @endif
                    <a href="{{ route('guidelines.index', ['locale' => app()->getLocale()]) }}"
                       class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        {{ cms('guidelines.observers-dialogue', 'foot.back_label') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
