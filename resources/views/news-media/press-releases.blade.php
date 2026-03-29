@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Press Releases',
    'subtitle' => 'Official statements and media releases from the ASEANAPOL Permanent Secretariat.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',                  ['locale' => app()->getLocale()])],
        ['label' => 'News & Media','url' => route('news-media.index',         ['locale' => app()->getLocale()])],
        ['label' => 'Press Releases', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$releases = [
    [
        'date'     => '25 Oct 2024',
        'title'    => '42nd ASEANAPOL Conference — Joint Communiqué',
        'summary'  => 'Heads of ASEANAPOL member police forces convened in Nay Pyi Taw, Myanmar, reaffirming regional commitment to combating transnational crime and adopted a new strategic framework for 2025–2030.',
        'category' => 'Conference',
        'slug'     => '42nd-aseanapol-conference',
    ],
    [
        'date'     => '19 Sep 2024',
        'title'    => 'ASEANAPOL Reaffirms Commitment to ASEAN Vision 2045 at the 9th Forum of Entities',
        'summary'  => 'The ASEANAPOL Secretariat participated in the 9th ASEAN Entities Forum in Bangkok, underscoring the organisation\'s alignment with ASEAN\'s long-term regional integration goals.',
        'category' => 'Cooperation',
        'slug'     => 'aseanapol-reaffirms-commitment-to-asean-vision-2045-at-the-9-forum-of-entities',
    ],
    [
        'date'     => '04 Jul 2025',
        'title'    => '13th ASEANAPOL Training Cooperation Meeting (ATCM)',
        'summary'  => 'The 13th ATCM was successfully convened in Yangon, Myanmar, adopting a hybrid format for the first time to maximise engagement from all AMCs, Dialogue Partners, and Observer Organisations.',
        'category' => 'Training',
        'slug'     => '13th-aseanapol-training-cooperation-meeting-(atcm)',
    ],
    [
        'date'     => '26 Jun 2025',
        'title'    => 'J.O.B. — Just Observe Before You Apply: ASEANAPOL Warns of Job Scams',
        'summary'  => 'ASEANAPOL issues a public advisory urging job seekers to verify employment offers carefully. Stay alert for fraudulent job advertisements using the ASEANAPOL name.',
        'category' => 'Advisory',
        'slug'     => 'j.o.b-',
    ],
    [
        'date'     => '15 May 2025',
        'title'    => 'ASEANAPOL\'s Strategic Role in Regional and Global Collaboration to Address Transnational Crime',
        'summary'  => 'At the World Police Summit 2025 in Dubai, ASEANAPOL reaffirmed its commitment to empowering a future-ready police force through training, information sharing, and multilateral cooperation.',
        'category' => 'Cooperation',
        'slug'     => 'empowering-a-future-ready-police-force-aseanapols-strategic-role-in-regional-and-global-collaboration-to-address-transnational-crime',
    ],
    [
        'date'     => '19 May 2025',
        'title'    => 'ASEANAPOL Advocates Regional Unity Against Ransomware at Southeast Asia Cybercrime Conference',
        'summary'  => 'The Executive Director of ASEANAPOL called for unified regional response to ransomware threats at the Southeast Asia Cybercrime Conference held in Kuala Lumpur.',
        'category' => 'Cybercrime',
        'slug'     => 'aseanapol-secretariat-executive-director-advocates-regional-unity-against-ransomware-at-southeast-asia-cybercrime-conference',
    ],
];

$categories = ['All', 'Conference', 'Cooperation', 'Training', 'Advisory', 'Cybercrime'];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Info banner --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                For media enquiries or to request a full press kit, contact the ASEANAPOL Permanent Secretariat at
                <a href="mailto:info@aseanapol.org" class="text-primary dark:text-accent font-semibold hover:underline">info@aseanapol.org</a>.
            </p>
        </div>

        {{-- Release list --}}
        <div class="space-y-4">
            @foreach($releases as $r)
            <article class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-5 hover:border-primary/30 dark:hover:border-primary/40 transition-colors">
                {{-- Date + category --}}
                <div class="sm:w-36 flex-shrink-0 flex sm:flex-col gap-3 sm:gap-2">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $r['date'] }}</span>
                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent uppercase tracking-wider w-fit">{{ $r['category'] }}</span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 leading-snug">
                        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $r['slug']]) }}"
                           class="hover:text-primary dark:hover:text-accent transition-colors">
                            {{ $r['title'] }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3">{{ $r['summary'] }}</p>
                    <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $r['slug']]) }}"
                       class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                        Read Full Release <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        {{-- More via News --}}
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Additional announcements and statements are published in the News section.
            </p>
            <a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">newspaper</span>
                Browse All News
            </a>
        </div>

    </div>
</section>
@endsection
