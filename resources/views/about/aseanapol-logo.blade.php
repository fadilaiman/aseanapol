@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'ASEANAPOL Logo',
    'subtitle' => 'The official ASEANAPOL logogramme — created and endorsed at the 3rd ASEANAPOL Conference, Jakarta, 1983.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'ASEANAPOL Logo',  'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Annotated Diagram --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-6 sm:p-10 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-6 text-center">Logogramme</h2>

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
                    <image href="{{ asset('images/aseanapol-logo.png') }}" x="290" y="48" width="300" height="360" />

                    {{-- ===== LEFT SIDE ARROWS ===== --}}

                    {{-- SHIELD --}}
                    <line x1="218" y1="88"  x2="320" y2="108" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="210" y="78"  text-anchor="end" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">SHIELD</text>
                    <text x="210" y="94"  text-anchor="end" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Symbol of community protection</text>

                    {{-- FLAGS --}}
                    <line x1="218" y1="178" x2="328" y2="192" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="210" y="170" text-anchor="end" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">FLAGS</text>
                    <text x="210" y="186" text-anchor="end" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">10 Member Countries</text>

                    {{-- YELLOW --}}
                    <line x1="218" y1="248" x2="332" y2="248" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="210" y="240" text-anchor="end" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">YELLOW</text>
                    <text x="210" y="256" text-anchor="end" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Color of Honesty</text>

                    {{-- SPRAY OF RICE & COTTON --}}
                    <line x1="218" y1="368" x2="312" y2="368" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="210" y="358" text-anchor="end" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">SPRAY OF RICE &amp; COTTON</text>
                    <text x="210" y="374" text-anchor="end" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Symbol of prosperity</text>

                    {{-- ===== RIGHT SIDE ARROWS ===== --}}

                    {{-- 3 STEPS ON TOP OF PILLAR --}}
                    <line x1="662" y1="108" x2="556" y2="112" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="670" y="98"  text-anchor="start" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">3 STEPS ON TOP OF PILLAR</text>
                    <text x="670" y="112" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Police's Motto:</text>
                    <text x="670" y="126" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Fight Crimes</text>
                    <text x="670" y="140" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Love Humanity</text>
                    <text x="670" y="154" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Help Delinquents</text>

                    {{-- 10 STEPS AT THE BOTTOM OF PILLAR --}}
                    <line x1="662" y1="318" x2="568" y2="335" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="670" y="310" text-anchor="start" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">10 STEPS AT THE BOTTOM OF PILLAR</text>
                    <text x="670" y="326" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">10 ASEAN Countries</text>

                    {{-- BLACK --}}
                    <line x1="662" y1="388" x2="580" y2="378" stroke="#7f1d1d" stroke-width="1.5" marker-end="url(#arr)" />
                    <text x="670" y="380" text-anchor="start" font-size="13" font-weight="700" fill="#1f2937" font-family="Inter,sans-serif">BLACK</text>
                    <text x="670" y="396" text-anchor="start" font-size="11" fill="#6b7280"    font-family="Inter,sans-serif">Firm Determination</text>

                </svg>
            </div>

            {{-- Caption --}}
            <div class="mt-6 text-center border-t border-gray-100 dark:border-gray-700 pt-6">
                <p class="font-bold text-gray-800 dark:text-white">Logogramme, 3rd ASEANAPOL Meeting, Jakarta,<br>16–19th November 1983</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto leading-relaxed">
                    Created &amp; endorsed during the 3rd ASEANAPOL Conference, 16–19th November 1983 in Jakarta, Indonesia.<br>
                    <span class="italic">(Originally with the 5 steps at the bottom of the pillar representing the then 5 member countries)</span>
                </p>
            </div>
        </div>

        {{-- Police Motto callout --}}
        <div class="bg-primary dark:bg-dark-card rounded-2xl p-6 text-center shadow-lg">
            <p class="text-accent font-semibold text-sm uppercase tracking-widest mb-3">Police Motto</p>
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                <span class="text-white font-bold text-xl">Fight Crimes</span>
                <span class="text-white/30 text-xl hidden sm:inline">·</span>
                <span class="text-white font-bold text-xl">Love Humanity</span>
                <span class="text-white/30 text-xl hidden sm:inline">·</span>
                <span class="text-white font-bold text-xl">Help Delinquents</span>
            </div>
        </div>

        {{-- Usage Guidelines link --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h3 class="font-semibold text-primary dark:text-white mb-1">Usage Guidelines</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">For rules on proper display and reproduction of the ASEANAPOL Flag and Logo.</p>
            </div>
            <a href="{{ route('guidelines.flag', ['locale' => app()->getLocale()]) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">description</span>
                View Guidelines
            </a>
        </div>

        <div>
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to About ASEANAPOL
            </a>
        </div>
    </div>
</section>

{{-- Dark mode: invert SVG text colors --}}
<style>
.dark svg text[fill="#1f2937"] { fill: #f9fafb; }
.dark svg text[fill="#6b7280"] { fill: #9ca3af; }
</style>
@endsection
