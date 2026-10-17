@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => $article->title,
    'subtitle' => $article->published_at ? $article->published_at->format('d F Y') : '',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.index', 'hero.title'),'url' => route('news-media.index',['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.news-article', 'labels.breadcrumb_news'),        'url' => route('news.index',     ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.news-article', 'labels.breadcrumb_article'),     'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Article meta --}}
        <div class="flex flex-wrap items-center gap-4 mb-8 text-sm text-gray-500 dark:text-gray-400">
            @if($article->published_at)
            <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">calendar_today</span>
                {{ $article->published_at->format('d F Y') }}
            </span>
            @endif
            @if($article->author)
            <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">person</span>
                {{ $article->author }}
            </span>
            @endif
            @if($article->sponsorItems())
            <div class="flex items-center gap-2 sm:ml-auto flex-shrink-0" data-testid="news-meta-sponsors">
                @foreach($article->sponsorItems() as $sp)
                <img src="{{ asset($sp['logo']) }}" alt="{{ $sp['name'] ?? '' }}" title="{{ $sp['name'] ?? '' }}" class="h-8 w-auto">
                @endforeach
            </div>
            @endif
        </div>

        {{-- Hero image: only shown when the content has no embedded images.
             Fitted, never cropped — a fair share of these thumbnails are portrait
             (event posters, standing group photos), and a fixed-height cover crop
             cut the top and bottom clean off them. --}}
        @if($article->thumbnail && !str_contains($article->content ?? '', '<img'))
        <div class="rounded-2xl overflow-hidden mb-8 shadow-sm border border-gray-100 dark:border-gray-700 lightbox-zone flex justify-center bg-gray-50 dark:bg-dark-card">
            {{-- inline max-height: the arbitrary-value Tailwind class would need an asset rebuild --}}
            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}"
                 class="w-auto max-w-full object-contain" style="max-height: 34rem;">
        </div>
        @endif

        {{-- Article body --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 sm:p-10">
            @if($article->content)
                <div class="prose prose-sm sm:prose dark:prose-invert max-w-none
                            prose-headings:text-primary dark:prose-headings:text-white
                            prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-xl prose-img:shadow-md lightbox-zone">
                    {!! $article->content !!}
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 italic">{{ cms('newsmedia.news-article', 'labels.no_content') }}</p>
            @endif
        </div>

        {{-- Supported by --}}
        @if($article->sponsorItems())
        <div class="mt-6 bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 px-8 sm:px-10 py-5 flex flex-wrap items-center gap-x-6 gap-y-3" data-testid="news-sponsors">
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $article->sponsorLabel() ?? cms('newsmedia.news-article', 'labels.supported_by') }}</span>
            <div class="flex flex-wrap items-center gap-4">
                @foreach($article->sponsorItems() as $sp)
                    @php $href = (!empty($sp['url']) && preg_match('#^https?://#i', $sp['url'])) ? $sp['url'] : null; @endphp
                    @if($href)
                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" title="{{ $sp['name'] ?? '' }}"><img src="{{ asset($sp['logo']) }}" alt="{{ $sp['name'] ?? '' }}" class="h-10 w-auto"></a>
                    @else
                    <img src="{{ asset($sp['logo']) }}" alt="{{ $sp['name'] ?? '' }}" title="{{ $sp['name'] ?? '' }}" class="h-10 w-auto">
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Navigation --}}
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('newsmedia.news-article', 'labels.back') }}
            </a>
        </div>

    </div>
</section>
@endsection
