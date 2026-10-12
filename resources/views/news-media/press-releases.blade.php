@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('newsmedia.press-releases', 'hero.title'),
    'subtitle' => cms('newsmedia.press-releases', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',                  ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.index', 'hero.title'),'url' => route('news-media.index',         ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.press-releases', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
@php $releases = cms('newsmedia.press-releases', 'releases.items'); @endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Info banner --}}
        @if(cms_on('newsmedia.press-releases', 'banner'))
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ cms('newsmedia.press-releases', 'banner.text') }}
                <a href="mailto:{{ cms('newsmedia.press-releases', 'banner.email') }}" class="text-primary dark:text-accent font-semibold hover:underline">{{ cms('newsmedia.press-releases', 'banner.email') }}</a>{{ cms('newsmedia.press-releases', 'banner.after') }}
            </p>
        </div>
        @endif

        {{-- Release list --}}
        @if(cms_on('newsmedia.press-releases', 'releases'))
        <div class="space-y-4">
            @foreach($releases as $r)
            <article class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-5 hover:border-primary/30 dark:hover:border-primary/40 transition-colors">
                {{-- Date + category --}}
                <div class="sm:w-36 flex-shrink-0 flex sm:flex-col gap-3 sm:gap-2">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $r['date'] }}</span>
                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent uppercase tracking-wider w-fit">{{ $r['category'] }}</span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 leading-snug">
                        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $r['slug']]) }}"
                           class="hover:text-primary dark:hover:text-accent transition-colors">
                            {{ $r['title'] }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3">{{ $r['summary'] }}</p>
                    <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $r['slug']]) }}"
                       class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                        {{ cms('newsmedia.press-releases', 'releases.read_more') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        {{-- More via News --}}
        @if(cms_on('newsmedia.press-releases', 'more'))
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ cms('newsmedia.press-releases', 'more.text') }}
            </p>
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">newspaper</span>
                {{ cms('newsmedia.press-releases', 'more.button') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
