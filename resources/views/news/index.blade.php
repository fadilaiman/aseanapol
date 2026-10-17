@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('newsmedia.news-list', 'hero.title'),
    'subtitle' => cms('newsmedia.news-list', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.index', 'hero.title'),'url' => route('news-media.index',['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.news-list', 'hero.title'),        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Active filter banner --}}
        @if($q !== '' && cms_on('newsmedia.news-search', 'filter'))
        <div class="flex items-center gap-3 mb-6 p-4 bg-accent/10 dark:bg-accent/15 border border-accent/30 rounded-xl">
            <span class="material-symbols-outlined text-accent text-lg">filter_alt</span>
            <p class="text-sm text-gray-700 dark:text-gray-200 flex-1">
                {{ cms('newsmedia.news-search', 'filter.text') }} <strong class="text-accent">{{ $q }}</strong>
            </p>
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-accent transition-colors">
                <span class="material-symbols-outlined text-sm">close</span> {{ cms('newsmedia.news-search', 'filter.clear') }}
            </a>
        </div>
        @endif

        {{-- Results count --}}
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
            @if($articles->total() > 0)
                {{ cms('newsmedia.news-list', 'labels.showing') }} {{ $articles->firstItem() }}–{{ $articles->lastItem() }} {{ cms('newsmedia.news-list', 'labels.of') }} {{ $articles->total() }} {{ cms('newsmedia.news-list', 'labels.articles') }}
            @else
                {{ cms('newsmedia.news-search', 'empty.summary') }}{{ $q !== '' ? ' ' . cms('newsmedia.news-search', 'empty.summary_for') . ' "' . $q . '"' : '' }}.
            @endif
        </p>

        {{-- Articles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($articles as $article)
            @php
                $excerpt = $article->summary
                    ?: strip_tags(html_entity_decode($article->content ?? ''));
                $excerpt = mb_substr(preg_replace('/\s+/', ' ', $excerpt), 0, 180);
                if (mb_strlen($excerpt) === 180) $excerpt .= '…';
            @endphp
            <article class="card-hover bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                {{-- Thumbnail or placeholder --}}
                <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $article->slug]) }}"
                   class="block h-44 flex-shrink-0 overflow-hidden">
                    @if($article->thumbnail)
                    <img src="{{ asset($article->thumbnail) }}"
                         alt="{{ $article->title }}"
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white/20 text-6xl">newspaper</span>
                    </div>
                    @endif
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3 text-xs text-gray-400 dark:text-gray-500">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        {{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug mb-3 flex-1 hover:text-primary dark:hover:text-accent transition-colors">
                        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $article->slug]) }}">{{ $article->title }}</a>
                    </h3>
                    @if($excerpt)
                    <p class="text-gray-500 dark:text-gray-400 text-xs leading-relaxed mb-4">{{ $excerpt }}</p>
                    @endif
                    <div class="flex items-center justify-between gap-3 mt-auto">
                        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $article->slug]) }}"
                           class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                            {{ cms('newsmedia.news-list', 'labels.read_more') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                        @if($article->sponsorItems())
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @foreach($article->sponsorItems() as $sp)
                            <img src="{{ asset($sp['logo']) }}" alt="{{ $sp['name'] ?? '' }}" class="h-8 w-auto">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-16 text-gray-400 dark:text-gray-500">
                <span class="material-symbols-outlined text-5xl mb-3 block">search_off</span>
                <p class="text-sm">{{ cms('newsmedia.news-search', 'empty.message') }} <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}" class="text-primary dark:text-accent hover:underline">{{ cms('newsmedia.news-search', 'empty.link') }}</a></p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $articles->links() }}
        </div>

    </div>
</section>
@endsection
