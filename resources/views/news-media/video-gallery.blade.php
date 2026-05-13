@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Video Gallery',
    'subtitle' => 'Video recordings of ASEANAPOL events, ceremonies, and training activities.',
    'breadcrumbs' => [
        ['label' => 'Home',          'url' => route('landing',              ['locale' => app()->getLocale()])],
        ['label' => 'News & Media',  'url' => route('news-media.index',     ['locale' => app()->getLocale()])],
        ['label' => 'Video Gallery', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($videos->isEmpty())
        {{-- Info banner (only when no videos yet) --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Video recordings of ASEANAPOL events and activities are being compiled and will be published here shortly.
                For media enquiries, contact the ASEANAPOL Permanent Secretariat at
                <a href="mailto:info@aseanapol.org" class="text-primary dark:text-accent font-semibold hover:underline">info@aseanapol.org</a>.
            </p>
        </div>
        @endif

        @if($videos->isNotEmpty())
        {{-- Video grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $v)
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                {{-- Thumbnail / embed / file --}}
                @if($v->embed_url)
                    <div class="aspect-video">
                        <iframe src="{{ $v->embed_url }}" title="{{ $v->title }}"
                                class="w-full h-full" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                @elseif($v->file_url)
                    <div class="aspect-video bg-black overflow-hidden relative">
                        <video controls preload="metadata" class="w-full h-full">
                            <source src="{{ asset($v->file_url) }}" type="video/mp4">
                        </video>
                        <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/80 dark:bg-dark-card/80 text-primary dark:text-accent uppercase tracking-wider pointer-events-none">{{ $v->category }}</span>
                    </div>
                @elseif($v->thumbnail_url)
                    <div class="aspect-video bg-gray-100 dark:bg-dark-surface overflow-hidden relative">
                        <img src="{{ $v->thumbnail_url }}" alt="{{ $v->title }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/80 dark:bg-dark-card/80 text-primary dark:text-accent uppercase tracking-wider">{{ $v->category }}</span>
                    </div>
                @else
                    <div class="aspect-video bg-gradient-to-br from-primary/10 to-primary/5 dark:from-primary/20 dark:to-primary/10 flex flex-col items-center justify-center gap-2 relative">
                        <span class="material-symbols-outlined text-primary/30 dark:text-primary/40 text-6xl">play_circle</span>
                        <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/80 dark:bg-dark-card/80 text-primary dark:text-accent uppercase tracking-wider">{{ $v->category }}</span>
                    </div>
                @endif

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug mb-2">{{ $v->title }}</h3>
                    @if($v->description)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 leading-relaxed">{{ $v->description }}</p>
                    @endif
                    <p class="text-xs text-gray-400 flex items-center gap-1 flex-wrap">
                        @if($v->event_date)
                            <span class="material-symbols-outlined text-sm">calendar_month</span>
                            {{ $v->event_date->format('F Y') }}
                        @endif
                        @if($v->event_date && $v->event_location)
                            <span class="mx-1">·</span>
                        @endif
                        @if($v->event_location)
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ $v->event_location }}
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Photo gallery CTA --}}
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Browse photos from ASEANAPOL events in the Photo Gallery.
            </p>
            <a href="{{ route('news-media.gallery', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">photo_library</span>
                Browse Photo Gallery
            </a>
        </div>

    </div>
</section>
@endsection
