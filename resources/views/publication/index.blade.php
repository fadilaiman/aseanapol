@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('data.publications-index', 'hero.title'),
    'subtitle' => cms('data.publications-index', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => cms('data.hub', 'hero.title'),'url' => route('data-resources.index',   ['locale' => app()->getLocale()])],
        ['label' => cms('data.publications-index', 'hero.title'),    'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(cms_on('data.publications-index', 'intro'))
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('data.publications-index', 'intro.text') }}
            </p>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($publications as $pub)
            @php $pubPdf = $pub->pdf_url; @endphp
            <div class="card-hover group bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-primary/20 dark:hover:border-accent/30 transition-all flex flex-col">
                {{-- Cover graphic --}}
                <div class="h-48 bg-gradient-to-br {{ $pub->color_class }} flex flex-col items-center justify-center p-6 relative">
                    @if($pub->cover_url)
                    <img src="{{ $pub->cover_url }}" alt="{{ $pub->title }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                    @endif
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 text-center">
                        <div class="text-white/60 text-xs uppercase tracking-widest font-semibold mb-2">{{ cms('data.publications-index', 'cards.brand') }}</div>
                        <div class="text-white font-extrabold text-4xl">{{ $pub->edition }}</div>
                        <div class="text-white/80 text-sm mt-1">{{ $pub->type }}</div>
                    </div>
                    <img src="{{ asset('images/aseanapol-logo.png') }}" alt="" class="absolute bottom-3 right-3 w-10 h-10 object-contain opacity-20">
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <span class="text-xs font-semibold {{ $pub->type === 'Magazine' ? 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20' : 'text-primary dark:text-accent bg-primary/8 dark:bg-primary/20' }} px-2.5 py-1 rounded-full self-start">
                        {{ $pub->type }}
                    </span>
                    <h3 class="font-bold text-gray-900 dark:text-white mt-3 mb-1">{{ $pub->title }}</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">{{ cms('data.publications-index', 'cards.caption') }}</p>
                    <div class="mt-auto pt-4">
                        @if(!empty($pubPdf))
                        <a href="{{ $pubPdf }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-base">download</span> {{ cms('data.publications-index', 'cards.btn_download') }}
                        </a>
                        @if(!empty($pub->size))
                        <div class="text-gray-400 dark:text-gray-500 text-xs mt-1.5">{{ str_replace('{size}', $pub->size, cms('data.publications-index', 'cards.size_format')) }}</div>
                        @endif
                        @else
                        <div class="flex items-center gap-1 text-gray-400 dark:text-gray-500 text-sm">
                            <span class="material-symbols-outlined text-base">lock</span> {{ cms('data.publications-index', 'cards.unavailable') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
