@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Newsletters',
    'subtitle' => 'Periodic newsletters and circulars distributed to ASEANAPOL member police forces and stakeholders.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',              ['locale' => app()->getLocale()])],
        ['label' => 'News & Media','url' => route('news-media.index',     ['locale' => app()->getLocale()])],
        ['label' => 'Newsletters', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Info banner --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Digital copies of ASEANAPOL newsletters will be made available for download as they are cleared for public release.
                Enquiries can be directed to
                <a href="mailto:info@aseanapol.org" class="text-primary dark:text-accent font-semibold hover:underline">info@aseanapol.org</a>.
            </p>
        </div>

        {{-- Newsletter list --}}
        @if($newsletters->isNotEmpty())
        <div class="space-y-4">
            @foreach($newsletters as $n)
            <article class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-5">
                {{-- Icon + date --}}
                <div class="sm:w-36 flex-shrink-0 flex sm:flex-col gap-3 sm:gap-2 items-start">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">mail</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $n->issue_date->format('M Y') }}</span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 leading-snug">{{ $n->title }}</h3>
                    @if($n->topics)
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($n->topics as $topic)
                        <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">{{ $topic }}</span>
                        @endforeach
                    </div>
                    @endif

                    @if($n->file_path)
                        <a href="{{ Storage::url($n->file_path) }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                            <span class="material-symbols-outlined text-base">download</span>
                            Download PDF
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-gray-400 dark:text-gray-500 text-sm">
                            <span class="material-symbols-outlined text-base">lock</span>
                            Download pending clearance
                        </span>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        @endif

        {{-- Publications CTA --}}
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                The ASEANAPOL Bulletin and Magazine series are available in the Publications section.
            </p>
            <a href="{{ route('data-resources.publications', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">menu_book</span>
                Browse Publications
            </a>
        </div>

    </div>
</section>
@endsection
