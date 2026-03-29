@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => $article->title,
    'subtitle' => $article->published_at ? $article->published_at->format('d F Y') : '',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => 'News & Media','url' => route('news-media.index',['locale' => app()->getLocale()])],
        ['label' => 'News',        'url' => route('news.index',     ['locale' => app()->getLocale()])],
        ['label' => 'Article',     'url' => ''],
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
        </div>

        {{-- Hero image --}}
        @if($article->thumbnail)
        <div class="rounded-2xl overflow-hidden mb-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}"
                 class="w-full max-h-96 object-cover">
        </div>
        @endif

        {{-- Article body --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 sm:p-10">
            @if($article->content)
                <div class="prose prose-sm sm:prose dark:prose-invert max-w-none
                            prose-headings:text-primary dark:prose-headings:text-white
                            prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-xl prose-img:shadow-md">
                    {!! $article->content !!}
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 italic">Content not available.</p>
            @endif
        </div>

        {{-- Navigation --}}
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to News
            </a>
        </div>

    </div>
</section>
@endsection
