@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => $title,
    'subtitle' => 'Official ASEANAPOL ' . $type,
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Publication', 'url' => route('publication.index', ['locale' => app()->getLocale()])],
        ['label' => $title,        'url' => ''],
    ],
])
@endsection

@section('content')
@php
$editionData = [
    '8th'  => ['year' => '2015', 'theme' => 'Regional Police Cooperation in the New Millennium',   'color' => 'from-blue-700 to-blue-900'],
    '9th'  => ['year' => '2016', 'theme' => 'Combating Transnational Crime Together',              'color' => 'from-primary to-primary/70'],
    '10th' => ['year' => '2017', 'theme' => 'Strengthening Partnerships for Regional Security',    'color' => 'from-indigo-700 to-indigo-900'],
    '11th' => ['year' => '2018', 'theme' => 'Building Resilient Police Forces',                    'color' => 'from-teal-700 to-teal-900'],
    '12th' => ['year' => '2019', 'theme' => 'ASEANAPOL: Four Decades of Cooperation',              'color' => 'from-primary/90 to-primary'],
    '13th' => ['year' => '2023', 'theme' => 'Toward a Safer ASEAN: Cooperation, Innovation, Impact', 'color' => 'from-amber-700 to-amber-900'],
];
$data = $editionData[$edition] ?? ['year' => '—', 'theme' => '', 'color' => 'from-primary to-primary/70'];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Cover --}}
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br {{ $data['color'] }} rounded-2xl p-8 flex flex-col items-center justify-center text-center shadow-lg min-h-72 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <img src="{{ asset('images/aseanapol-logo.png') }}" alt="ASEANAPOL" class="w-16 h-16 object-contain mx-auto mb-4 opacity-80">
                        <div class="text-white/70 text-xs uppercase tracking-widest font-semibold mb-1">ASEANAPOL</div>
                        <div class="text-white font-extrabold text-5xl mb-1">{{ $edition }}</div>
                        <div class="text-white/80 text-sm">Edition {{ $type }}</div>
                        @if($data['year'] !== '—')
                        <div class="text-white/60 text-xs mt-2">{{ $data['year'] }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-accent uppercase tracking-wider">{{ $type }}</span>
                        @if($data['year'] !== '—')
                        <span class="text-xs text-gray-400">· {{ $data['year'] }}</span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white mb-3">{{ $title }}</h2>
                    @if($data['theme'])
                    <p class="text-gray-500 dark:text-gray-400 italic mb-5">"{{ $data['theme'] }}"</p>
                    @endif
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        The {{ $title }} is an official publication of the ASEANAPOL Permanent Secretariat. It documents the activities, programmes, and achievements of ASEANAPOL and its member police forces, serving as a key resource for police professionals, researchers, and stakeholders across the ASEAN region.
                    </p>
                </div>

                {{-- Contents overview --}}
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-primary dark:text-white mb-5">Typical Contents</h3>
                    @php
                    $contents = [
                        ['icon' => 'article',       'text' => 'Message from the ASEANAPOL Executive Director'],
                        ['icon' => 'groups',        'text' => 'Annual Conference highlights and resolutions'],
                        ['icon' => 'task_alt',      'text' => 'Working committee reports and achievements'],
                        ['icon' => 'handshake',     'text' => 'Partnership and collaboration updates'],
                        ['icon' => 'school',        'text' => 'Training and capacity building activities'],
                        ['icon' => 'newspaper',     'text' => 'Member police force news and updates'],
                    ];
                    @endphp
                    <ul class="space-y-3">
                        @foreach($contents as $c)
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">{{ $c['icon'] }}</span>
                            <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $c['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Download --}}
                <div class="bg-primary/5 dark:bg-primary/15 rounded-2xl p-6 border border-primary/10 dark:border-primary/30">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0">picture_as_pdf</span>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Download PDF</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                                To request a copy of this publication, please contact the ASEANAPOL Permanent Secretariat.
                            </p>
                            <a href="mailto:aseanapolsec@aseanapol.org?subject=Request: {{ $title }}"
                               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-lg">mail</span>
                                Request This Publication
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('publication.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to Publications
            </a>
        </div>
    </div>
</section>
@endsection
