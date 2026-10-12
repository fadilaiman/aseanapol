@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => $pub->title,
    'subtitle' => str_replace('{type}', $pub->type, cms('data.publication-edition', 'hero.subtitle')),
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                  ['locale' => app()->getLocale()])],
        ['label' => cms('data.hub', 'hero.title'), 'url' => route('data-resources.index',     ['locale' => app()->getLocale()])],
        ['label' => cms('data.publications-index', 'hero.title'),     'url' => route('data-resources.publications',['locale' => app()->getLocale()])],
        ['label' => $pub->title,         'url' => ''],
    ],
])
@endsection

@section('content')
@php
$title = $pub->title;
$type  = $pub->type;
$edition = $pub->edition;
$pdfUrl = $pub->pdf_url;
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Cover --}}
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br {{ $pub->color_class }} rounded-2xl p-8 flex flex-col items-center justify-center text-center shadow-lg min-h-72 relative overflow-hidden">
                    @if($pub->cover_url)
                    <img src="{{ $pub->cover_url }}" alt="{{ $pub->title }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        @if(cms_img('shared.organisation', 'identity.logo'))<img src="{{ cms_img('shared.organisation', 'identity.logo') }}" alt="ASEANAPOL" class="w-16 h-16 object-contain mx-auto mb-4 opacity-80">@endif
                        <div class="text-white/70 text-xs uppercase tracking-widest font-semibold mb-1">{{ cms('data.publication-edition', 'cover.brand') }}</div>
                        <div class="text-white font-extrabold text-5xl mb-1">{{ $edition }}</div>
                        <div class="text-white/80 text-sm">{{ str_replace('{type}', $type, cms('data.publication-edition', 'cover.edition_label')) }}</div>
                        @if(!empty($pub->year))
                        <div class="text-white/60 text-xs mt-2">{{ $pub->year }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-accent uppercase tracking-wider">{{ $type }}</span>
                        @if(!empty($pub->year))
                        <span class="text-xs text-gray-400">· {{ $pub->year }}</span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white mb-3">{{ $title }}</h2>
                    @if($pub->theme)
                    <p class="text-gray-500 dark:text-gray-400 italic mb-5">"{{ $pub->theme }}"</p>
                    @endif
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ $pub->summary ?: str_replace('{title}', $title, cms('data.publication-edition', 'intro.text')) }}
                    </p>
                </div>

                {{-- Contents overview --}}
                @if(cms_on('data.publication-edition', 'contents'))
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-primary dark:text-white mb-5">{{ cms('data.publication-edition', 'contents.heading') }}</h3>
                    <ul class="space-y-3">
                        @foreach(cms('data.publication-edition', 'contents.items') as $c)
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">{{ $c['icon'] }}</span>
                            <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $c['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Download --}}
                <div class="bg-primary/5 dark:bg-primary/15 rounded-2xl p-6 border border-primary/10 dark:border-primary/30">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0">picture_as_pdf</span>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ cms('data.publication-edition', 'download.heading') }}</h4>
                            @if(!empty($pdfUrl))
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                                {{ cms('data.publication-edition', 'download.available') }}
                                @if(!empty($pub->size))
                                    <span class="text-gray-400 dark:text-gray-500">{{ str_replace('{size}', $pub->size, cms('data.publication-edition', 'download.size_format')) }}</span>
                                @endif
                            </p>
                            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-lg">download</span>
                                {{ cms('data.publication-edition', 'download.button') }}
                            </a>
                            @else
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                                {{ cms('data.publication-edition', 'download.unavailable') }}
                            </p>
                            @if(shared('secretariat', 'contact.email_secretariat'))
                            <a href="mailto:{{ shared('secretariat', 'contact.email_secretariat') }}?subject={{ str_replace('{title}', $title, cms('data.publication-edition', 'download.request_subject')) }}"
                               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-lg">mail</span>
                                {{ cms('data.publication-edition', 'download.request_button') }}
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('data-resources.publications', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('data.publication-edition', 'back.label') }}
            </a>
        </div>
    </div>
</section>
@endsection
