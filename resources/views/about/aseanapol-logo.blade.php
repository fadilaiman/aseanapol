@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.logo', 'hero.title'),
    'subtitle' => cms('about.logo', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.logo', 'hero.title'),  'url' => ''],
    ],
])
@endsection

@section('content')
@php
// Line breaks typed in the admin become <br>.
$br = fn ($t) => new \Illuminate\Support\HtmlString(str_replace("\n", '<br>', e((string) $t)));
@endphp
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Annotated Diagram --}}
        @if(cms_on('about.logo', 'diagram'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-6 sm:p-10 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-6 text-center">{{ cms('about.logo', 'diagram.heading') }}</h2>

            <div class="overflow-x-auto">
                <svg viewBox="0 0 880 500" xmlns="http://www.w3.org/2000/svg"
                     class="w-full max-w-3xl mx-auto block" style="min-width:480px">

                    <defs>
                        {{-- Dark red arrowhead marker --}}
                        <marker id="arr" markerWidth="8" markerHeight="6" refX="7" refY="3" orient="auto">
                            <polygon points="0 0, 8 3, 0 6" fill="#7f1d1d" class="dark-arrow" />
                        </marker>
                    </defs>

                    {{-- Logo image centered --}}
                    @if(cms_img('about.logo', 'diagram.logo'))
                    <image href="{{ cms_img('about.logo', 'diagram.logo') }}" x="290" y="48" width="300" height="360" />
                    @endif

                    {{-- ===== LEFT SIDE ARROWS ===== --}}
                    @foreach(cms('about.logo', 'diagram.left') as $c)
                    <line x1="{{ $c['x1'] }}" y1="{{ $c['y1'] }}" x2="{{ $c['x2'] }}" y2="{{ $c['y2'] }}" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="210" y="{{ $c['ty'] }}" text-anchor="end" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">{{ $c['title'] }}</text>
                    @foreach($c['lines'] as $li => $ln)
                    <text x="210" y="{{ $c['ty'] + $c['gap'] + 14 * $li }}" text-anchor="end" font-size="11" fill="#6b7280" font-family="Inter,sans-serif">{{ $ln }}</text>
                    @endforeach
                    @endforeach

                    {{-- ===== RIGHT SIDE ARROWS ===== --}}
                    @foreach(cms('about.logo', 'diagram.right') as $c)
                    <line x1="{{ $c['x1'] }}" y1="{{ $c['y1'] }}" x2="{{ $c['x2'] }}" y2="{{ $c['y2'] }}" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="670" y="{{ $c['ty'] }}" text-anchor="start" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">{{ $c['title'] }}</text>
                    @foreach($c['lines'] as $li => $ln)
                    <text x="670" y="{{ $c['ty'] + $c['gap'] + 14 * $li }}" text-anchor="start" font-size="11" fill="#6b7280" font-family="Inter,sans-serif">{{ $ln }}</text>
                    @endforeach
                    @endforeach

                </svg>
            </div>

            {{-- Caption --}}
            <div class="mt-6 text-center border-t border-gray-100 dark:border-gray-700 pt-6">
                <p class="font-bold text-gray-800 dark:text-white">{{ $br(cms('about.logo', 'diagram.caption_title')) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto leading-relaxed">
                    {{ cms('about.logo', 'diagram.caption_text') }}<br>
                    <span class="italic">{{ cms('about.logo', 'diagram.caption_note') }}</span>
                </p>
            </div>
        </div>
        @endif

        {{-- Police Motto callout --}}
        @if(cms_on('about.logo', 'motto'))
        <div class="bg-primary dark:bg-dark-card rounded-2xl p-6 text-center shadow-lg">
            <p class="text-accent font-semibold text-sm uppercase tracking-widest mb-3">{{ cms('about.logo', 'motto.label') }}</p>
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                @foreach(cms('about.logo', 'motto.items') as $m)
                @if(!$loop->first)
                <span class="text-white/30 text-xl hidden sm:inline">·</span>
                @endif
                <span class="text-white font-bold text-xl">{{ $m['text'] }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Usage Guidelines link --}}
        @if(cms_on('about.logo', 'guidelines'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h3 class="font-semibold text-primary dark:text-white mb-1">{{ cms('about.logo', 'guidelines.heading') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ cms('about.logo', 'guidelines.text') }}</p>
            </div>
            <a href="{{ route('guidelines.flag', ['locale' => app()->getLocale()]) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">{{ cms('about.logo', 'guidelines.icon') }}</span>
                {{ cms('about.logo', 'guidelines.button') }}
            </a>
        </div>
        @endif

        @if(cms_on('about.logo', 'back'))
        <div>
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about.logo', 'back.label') }}
            </a>
        </div>
        @endif
    </div>
</section>

{{-- Dark mode: invert SVG text colors --}}
<style>
.dark svg text[fill="#1f2937"] { fill: #f9fafb; }
.dark svg text[fill="#6b7280"] { fill: #9ca3af; }
</style>
@endsection
