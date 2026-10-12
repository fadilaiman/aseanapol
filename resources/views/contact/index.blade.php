@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('site.contact', 'hero.title'),
    'subtitle' => cms('site.contact', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',       'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('site.contact', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$P = 'site.contact';
$members = cms($P, 'directory.items');
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- Top two-column: Secretariat Info + Inquiry Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- 9.2 — Secretariat Contact Information --}}
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-1">{{ cms($P, 'secretariat.heading') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ cms($P, 'secretariat.subheading') }}</p>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700/50 overflow-hidden">
                    {{-- Address --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">location_on</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ cms($P, 'secretariat.address_label') }}</div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                {!! implode("<br>\n", array_map('e', preg_split('/\R/', (string) cms($P, 'secretariat.address')))) !!}
                            </p>
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">call</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ cms($P, 'secretariat.phone_label') }}</div>
                            <a href="{{ cms($P, 'secretariat.phone_link') }}" class="text-sm text-primary dark:text-accent hover:underline">{{ cms($P, 'secretariat.phone') }}</a>
                        </div>
                    </div>
                    {{-- Fax --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">fax</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ cms($P, 'secretariat.fax_label') }}</div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ cms($P, 'secretariat.fax') }}</span>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="p-5 flex gap-4">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">mail</span>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ cms($P, 'secretariat.email_label') }}</div>
                            <a href="mailto:{{ cms($P, 'secretariat.email') }}" class="text-sm text-primary dark:text-accent hover:underline">{{ cms($P, 'secretariat.email') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Office Hours note --}}
                @if(cms_on($P, 'hours'))
                <div class="bg-primary/5 dark:bg-primary/15 rounded-xl p-4 border border-primary/10 dark:border-primary/20 text-sm text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined text-base text-primary dark:text-accent align-middle mr-1">schedule</span>
                    {{ cms($P, 'hours.text') }}
                </div>
                @endif
            </div>

            {{-- Google Maps --}}
            @if(cms_on($P, 'map'))
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full min-h-[420px]">
                    <iframe
                        src="{{ cms($P, 'map.src') }}"
                        width="100%"
                        height="100%"
                        style="min-height: 420px; border: 0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            @endif
        </div>

        @if(trim((string) cms($P, 'form.heading')) !== '')
        {{-- Inquiry form (optional) --}}
        <div class="max-w-3xl mx-auto w-full" id="inquiry">
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-1">{{ cms($P, 'form.heading') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ cms($P, 'form.intro') }}</p>
            @if(session('contact_success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm" data-testid="contact-success">{{ session('contact_success') }}</div>
            @endif
            @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc pl-5">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
            @endif
            <form method="POST" action="{{ route('contact-us.submit', ['locale' => app()->getLocale()]) }}#inquiry" class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ cms($P, 'form.name_label') }}
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="120" placeholder="{{ cms($P, 'form.name_placeholder') }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-surface px-3 py-2 text-sm">
                    </label>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ cms($P, 'form.email_label') }}
                        <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" placeholder="{{ cms($P, 'form.email_placeholder') }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-surface px-3 py-2 text-sm">
                    </label>
                </div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ cms($P, 'form.subject_label') }}
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200" placeholder="{{ cms($P, 'form.subject_placeholder') }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-surface px-3 py-2 text-sm">
                </label>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ cms($P, 'form.message_label') }}
                    <textarea name="message" rows="6" required minlength="10" maxlength="5000" placeholder="{{ cms($P, 'form.message_placeholder') }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-surface px-3 py-2 text-sm">{{ old('message') }}</textarea>
                </label>
                <button type="submit" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-lg">send</span>
                    {{ cms($P, 'form.button') }}
                </button>
            </form>
        </div>
        @endif

        {{-- 9.4 — Member Directory --}}
        @if(cms_on($P, 'directory'))
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">contacts</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-primary dark:text-white">{{ cms($P, 'directory.heading') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ cms($P, 'directory.subheading') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead class="bg-gray-50 dark:bg-dark-surface border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-48">{{ cms($P, 'directory.col_country') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ cms($P, 'directory.col_org') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-44 hidden md:table-cell">{{ cms($P, 'directory.col_phone') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-36 hidden lg:table-cell">{{ cms($P, 'directory.col_fax') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 w-52">{{ cms($P, 'directory.col_contact') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach($members as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ asset('images/flags/' . $m['flag'] . '.svg') }}"
                                             onerror="this.style.display='none'"
                                             alt="{{ $m['country'] }}" class="w-6 h-4 object-cover rounded-sm flex-shrink-0">
                                        <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $m['country'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 text-xs leading-relaxed">{{ $m['org'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 text-xs hidden md:table-cell">{{ $m['tel'] }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs hidden lg:table-cell whitespace-nowrap">{{ $m['fax'] ?? '—' }}</td>
                                <td class="px-6 py-4 text-xs">
                                    @if(!empty($m['email']))
                                    <a href="mailto:{{ $m['email'] }}" class="text-primary dark:text-accent hover:underline break-all">{{ $m['email'] }}</a>
                                    @elseif(!empty($m['web']))
                                    <a href="{{ $m['web'] }}" target="_blank" rel="noopener" class="text-primary dark:text-accent hover:underline">{{ cms($P, 'directory.portal_label') }}</a>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
