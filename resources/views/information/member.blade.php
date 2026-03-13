@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => $force,
    'subtitle' => $country,
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Information',      'url' => route('information.index', ['locale' => app()->getLocale()])],
        ['label' => $country,           'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Flag + Force Name --}}
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-6">
                    <img src="https://flagcdn.com/w160/{{ $iso }}.png"
                         alt="{{ $country }} flag"
                         class="w-24 h-auto rounded-md shadow-md flex-shrink-0">
                    <div>
                        <div class="text-sm font-semibold text-accent uppercase tracking-wider mb-1">Member Country</div>
                        <h2 class="text-2xl font-bold text-primary dark:text-white">{{ $force }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $country }}</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-primary dark:text-white mb-4">Overview</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $description }}</p>
                </div>

                {{-- ASEANAPOL Membership Note --}}
                <div class="bg-primary/5 dark:bg-primary/15 rounded-2xl p-6 border border-primary/10 dark:border-primary/30">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                            {{ $country }} is one of the ten member states of ASEANAPOL, contributing to the collective mission of enhancing regional police cooperation and combating transnational crime in Southeast Asia.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sidebar: Contact --}}
            <div class="space-y-6">
                <div class="bg-primary dark:bg-dark-card rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="font-semibold text-lg mb-5 text-accent">ASEANAPOL Contact</h3>
                    <div class="space-y-4 text-sm">
                        @if(!empty($tel))
                            @foreach((array)$tel as $t)
                            <div class="flex gap-3 items-center">
                                <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">phone</span>
                                <a href="tel:{{ preg_replace('/\s+/', '', $t) }}" class="text-white/80 hover:text-accent transition-colors">{{ $t }}</a>
                            </div>
                            @endforeach
                        @endif

                        @if(!empty($fax))
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">fax</span>
                            <p class="text-white/80">{{ $fax }}</p>
                        </div>
                        @endif

                        @if(!empty($email))
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">mail</span>
                            <a href="mailto:{{ $email }}" class="text-white/80 hover:text-accent transition-colors break-all">{{ $email }}</a>
                        </div>
                        @endif

                        @if(!empty($website))
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">language</span>
                            <a href="{{ $website }}" target="_blank" rel="noopener" class="text-white/80 hover:text-accent transition-colors break-all">{{ str_replace(['https://', 'http://'], '', rtrim($website, '/')) }}</a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Navigation --}}
                <a href="{{ route('information.index', ['locale' => app()->getLocale()]) }}"
                   class="flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Back to Member Countries
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
