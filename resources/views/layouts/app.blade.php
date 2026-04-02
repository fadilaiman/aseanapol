<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ASEANAPOL - ASEAN Chiefs of Police official portal. Together we keep this region safe.">
    <title>{{ config('app.name', 'ASEANAPOL') }} - ASEAN Chiefs of Police</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#013565',
                            50: '#e6eef6',
                            100: '#b3cde3',
                            200: '#80acd0',
                            300: '#4d8bbd',
                            400: '#1a6aaa',
                            500: '#013565',
                            600: '#012d57',
                            700: '#012548',
                            800: '#011d3a',
                            900: '#00152c',
                        },
                        accent: {
                            DEFAULT: '#D4AF37',
                            50: '#faf6e8',
                            100: '#f0e4b8',
                            200: '#e6d288',
                            300: '#dcc058',
                            400: '#D4AF37',
                            500: '#b8962e',
                            600: '#9c7d25',
                            700: '#80641c',
                            800: '#644b13',
                            900: '#48320a',
                        },
                        background: '#f5f7f8',
                        dark: {
                            DEFAULT: '#0f1923',
                            card: '#1a2736',
                            surface: '#162030',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    {{-- Dark mode init (before paint) --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        .glass-nav {
            background: rgba(1, 53, 101, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-nav {
            background: rgba(15, 25, 35, 0.95);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(1, 53, 101, 0.93) 0%, rgba(1, 53, 101, 0.75) 50%, rgba(1, 53, 101, 0.6) 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }
        .dark .card-hover:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        /* Mobile menu */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .mobile-menu.open {
            max-height: 600px;
        }
        /* Dropdown */
        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.15s ease, transform 0.15s ease;
        }
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        /* Back to top */
        #back-to-top {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #back-to-top.visible {
            opacity: 1;
            pointer-events: auto;
        }
        /* Nav dropdowns — desktop hover */
        .nav-group:hover > .nav-drop,
        .nav-group:focus-within > .nav-drop {
            display: block;
        }
        .nav-drop {
            display: none;
        }
        /* Mobile accordion */
        .mob-sub {
            display: none;
        }
        .mob-sub.open {
            display: block;
        }
        /* Coming soon badge */
        .badge-soon {
            font-size: 0.6rem;
            line-height: 1;
            padding: 2px 5px;
            border-radius: 4px;
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .dark .badge-soon {
            background: rgba(217,119,6,0.2);
            color: #fcd34d;
        }
    </style>
</head>
<body class="font-sans bg-background dark:bg-dark text-gray-800 dark:text-gray-200 antialiased transition-colors duration-300">

    {{-- Header --}}
    <header id="main-header" class="glass-nav fixed top-0 left-0 right-0 z-50 transition-shadow duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="{{ route('landing', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('images/aseanapol-logo.png') }}" alt="ASEANAPOL Logo" class="h-10 lg:h-14 w-auto">
                    <div class="hidden sm:block">
                        <div class="text-white font-bold text-lg lg:text-xl tracking-wide leading-tight">ASEANAPOL</div>
                        <div class="text-accent text-xs lg:text-sm font-medium tracking-widest uppercase">Secretariat</div>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                @php $loc = ['locale' => app()->getLocale()]; @endphp
                <nav class="hidden xl:flex items-center gap-0.5">

                    {{-- About ASEANAPOL --}}
                    <div class="nav-group relative">
                        <a href="{{ route('about.index', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            About ASEANAPOL <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-56 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('about.chronology', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">History</a>
                            <a href="{{ route('about.permanent-secretariat', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Permanent Secretariat</a>
                            <a href="{{ route('about.governance', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Governance</a>
                            <a href="{{ route('about.vision-mission', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Vision &amp; Mission</a>
                            <a href="{{ route('about.leadership', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Leadership</a>
                            <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                            <a href="{{ route('about.member-countries', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Member Countries</a>
                            <a href="{{ route('about.dialogue-partners', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Dialogue Partners</a>
                            <a href="{{ route('about.observers', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Observers</a>
                            <a href="{{ route('about.ob-lme', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Like-Minded Entities (LME)</a>
                        </div>
                    </div>

                    {{-- News & Media --}}
                    <div class="nav-group relative">
                        <a href="{{ route('news-media.index', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            News &amp; Media <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-52 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('news.index', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">News</a>
                            <a href="{{ route('news-media.press-releases', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Press Releases</a>
                            <a href="{{ route('news-media.gallery', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Photo Gallery</a>
                            <a href="{{ route('news-media.video-gallery', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Video Gallery</a>
                            <a href="{{ route('news-media.newsletters', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Newsletters</a>
                        </div>
                    </div>

                    {{-- Data & Resources --}}
                    <div class="nav-group relative">
                        <a href="{{ route('data-resources.index', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            Data &amp; Resources <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-56 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('data-resources.crime-statistics', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Crime Statistics</a>
                            <a href="{{ route('data-resources.publications', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Publications</a>
                            <a href="{{ route('data-resources.digital-library', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Digital Library</a>
                        </div>
                    </div>

                    {{-- International Cooperation --}}
                    <div class="nav-group relative">
                        <a href="{{ route('international-cooperation', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            Intl. Cooperation <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-48 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('international-cooperation.interpol', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">INTERPOL</a>
                            <a href="{{ route('international-cooperation.europol', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Europol</a>
                            <a href="{{ route('international-cooperation.unodc', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">UNODC</a>
                            <a href="{{ route('international-cooperation.joint-projects', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Joint Projects</a>
                        </div>
                    </div>

                    {{-- Events & Training --}}
                    <div class="nav-group relative">
                        <a href="{{ route('events-training.index', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            Events <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-48 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('events-training.calendar', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Event Calendar</a>
                            <a href="{{ route('events-training.conferences', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Conferences</a>
                            <a href="{{ route('events-training.training-programs', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Training Programs</a>
                        </div>
                    </div>

                    {{-- Careers --}}
                    <div class="nav-group relative">
                        <a href="{{ route('careers.index', $loc) }}" class="flex items-center gap-0.5 text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                            Careers <span class="material-symbols-outlined text-sm leading-none">expand_more</span>
                        </a>
                        <div class="nav-drop absolute top-full left-0 mt-0 w-48 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('careers.vacancies', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Vacancies</a>
                            <a href="{{ route('careers.internships', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Internships</a>
                            <a href="{{ route('careers.exchange-programs', $loc) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-surface hover:text-primary transition-colors">Exchange Programs</a>
                        </div>
                    </div>

                    {{-- Contact Us --}}
                    <a href="{{ route('contact-us', $loc) }}" class="text-white/90 hover:text-accent text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-white/10 transition-all uppercase tracking-wide whitespace-nowrap">
                        Contact Us
                    </a>

                </nav>

                {{-- Right actions --}}
                <div class="flex items-center gap-2">
                    {{-- Language Switcher --}}
                    <div class="relative">
                        <button onclick="toggleDropdown('lang-dropdown')" class="flex items-center gap-1.5 text-white/80 hover:text-white px-2 py-1.5 rounded-lg hover:bg-white/10 transition-all text-sm">
                            <span class="material-symbols-outlined text-lg">translate</span>
                            <span class="hidden sm:inline uppercase font-medium">{{ app()->getLocale() }}</span>
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </button>
                        <div id="lang-dropdown" class="dropdown-menu absolute right-0 top-full mt-2 bg-white dark:bg-dark-card rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 min-w-[160px] z-50">
                            @php
                                $languages = [
                                    'en' => ['label' => 'English',      'flag' => 'gb'],
                                    'ms' => ['label' => 'Melayu',       'flag' => 'my'],
                                    'id' => ['label' => 'Indonesia',    'flag' => 'id'],
                                    'th' => ['label' => 'ภาษาไทย',      'flag' => 'th'],
                                    'vi' => ['label' => 'Tiếng Việt',   'flag' => 'vn'],
                                    'km' => ['label' => 'ភាសាខ្មែរ',   'flag' => 'kh'],
                                    'lo' => ['label' => 'ພາສາລາວ',      'flag' => 'la'],
                                    'my' => ['label' => 'မြန်မာ',       'flag' => 'mm'],
                                    'tl' => ['label' => 'Filipino',     'flag' => 'ph'],
                                ];
                                $currentRouteName   = \Illuminate\Support\Facades\Route::currentRouteName() ?? 'landing';
                                $currentRouteParams = \Illuminate\Support\Facades\Route::current()?->parameters() ?? [];
                            @endphp
                            @foreach($languages as $code => $lang)
                                <a href="{{ route($currentRouteName, array_merge($currentRouteParams, ['locale' => $code])) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors {{ app()->getLocale() === $code ? 'text-accent font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                    <img src="https://flagcdn.com/w40/{{ $lang['flag'] }}.png" alt="{{ $lang['label'] }}" class="w-6 h-auto rounded-sm">
                                    {{ $lang['label'] }}
                                    @if(app()->getLocale() === $code)
                                        <span class="material-symbols-outlined text-accent text-base ml-auto">check</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <button onclick="toggleTheme()" id="theme-toggle" class="text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-all" title="{{ __('landing.theme_dark') }}">
                        <span class="material-symbols-outlined text-xl" id="theme-icon">dark_mode</span>
                    </button>

                    {{-- Search --}}
                    <div class="hidden lg:flex items-center bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="material-symbols-outlined text-white/70 text-xl mr-2">search</span>
                        <input type="text" placeholder="{{ __('landing.nav_search') }}" class="bg-transparent border-none outline-none text-white text-sm placeholder-white/50 w-28">
                    </div>

                    {{-- Publications CTA --}}
                    <a href="{{ route('data-resources.publications', ['locale' => app()->getLocale()]) }}" class="hidden md:inline-flex items-center gap-2 bg-accent hover:bg-accent-600 text-primary font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-lg">menu_book</span>
                        {{ __('landing.nav_publication') }}
                    </a>

                    {{-- Mobile menu toggle --}}
                    <button onclick="toggleMobileMenu()" class="xl:hidden text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-2xl" id="menu-icon">menu</span>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            @php $loc = ['locale' => app()->getLocale()]; @endphp
            <div id="mobile-menu" class="mobile-menu xl:hidden pb-4">
                <nav class="flex flex-col gap-0.5 border-t border-white/10 pt-3">

                    <a href="{{ route('landing', $loc) }}" class="text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">Home</a>

                    {{-- About --}}
                    <div>
                        <button onclick="toggleMobSub('mob-about')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            About ASEANAPOL <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-about" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('about.chronology', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">History</a>
                            <a href="{{ route('about.permanent-secretariat', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Permanent Secretariat</a>
                            <a href="{{ route('about.governance', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Governance</a>
                            <a href="{{ route('about.vision-mission', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Vision &amp; Mission</a>
                            <a href="{{ route('about.leadership', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Leadership</a>
                            <a href="{{ route('about.member-countries', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Member Countries</a>
                            <a href="{{ route('about.dialogue-partners', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Dialogue Partners</a>
                            <a href="{{ route('about.observers', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Observers</a>
                            <a href="{{ route('about.ob-lme', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Like-Minded Entities (LME)</a>
                        </div>
                    </div>

                    {{-- News & Media --}}
                    <div>
                        <button onclick="toggleMobSub('mob-news')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            News &amp; Media <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-news" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('news.index', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">News</a>
                            <a href="{{ route('news-media.press-releases', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Press Releases</a>
                            <a href="{{ route('news-media.gallery', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Photo Gallery</a>
                            <a href="{{ route('news-media.video-gallery', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Video Gallery</a>
                            <a href="{{ route('news-media.newsletters', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Newsletters</a>
                        </div>
                    </div>

                    {{-- Data & Resources --}}
                    <div>
                        <button onclick="toggleMobSub('mob-data')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            Data &amp; Resources <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-data" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('data-resources.crime-statistics', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Crime Statistics</a>
                            <a href="{{ route('data-resources.publications', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Publications</a>
                            <a href="{{ route('data-resources.digital-library', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Digital Library</a>
                        </div>
                    </div>

                    {{-- International Cooperation --}}
                    <div>
                        <button onclick="toggleMobSub('mob-intl')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            International Cooperation <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-intl" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('international-cooperation.interpol', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">INTERPOL</a>
                            <a href="{{ route('international-cooperation.europol', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Europol</a>
                            <a href="{{ route('international-cooperation.unodc', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">UNODC</a>
                            <a href="{{ route('international-cooperation.joint-projects', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Joint Projects</a>
                        </div>
                    </div>

                    {{-- Events & Training --}}
                    <div>
                        <button onclick="toggleMobSub('mob-events')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            Events &amp; Training <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-events" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('events-training.calendar', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Event Calendar</a>
                            <a href="{{ route('events-training.conferences', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Conferences</a>
                            <a href="{{ route('events-training.training-programs', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Training Programs</a>
                        </div>
                    </div>

                    {{-- Careers --}}
                    <div>
                        <button onclick="toggleMobSub('mob-careers')" class="w-full flex items-center justify-between text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                            Careers &amp; Opportunities <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div id="mob-careers" class="mob-sub pl-4 flex flex-col gap-0.5">
                            <a href="{{ route('careers.vacancies', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Vacancies</a>
                            <a href="{{ route('careers.internships', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Internships</a>
                            <a href="{{ route('careers.exchange-programs', $loc) }}" class="text-white/70 hover:text-accent text-sm px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Exchange Programs</a>
                        </div>
                    </div>

                    <a href="{{ route('contact-us', $loc) }}" class="text-white/90 hover:text-accent text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-all">Contact Us</a>

                    {{-- Language switcher --}}
                    <div class="flex items-center gap-2 px-3 py-2 mt-2 border-t border-white/10 pt-3">
                        @foreach($languages as $code => $lang)
                            <a href="{{ route($currentRouteName, array_merge($currentRouteParams, ['locale' => $code])) }}"
                               class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm {{ app()->getLocale() === $code ? 'bg-accent text-primary font-semibold' : 'bg-white/10 text-white/80' }} transition-colors">
                                <img src="https://flagcdn.com/w40/{{ $lang['flag'] }}.png" alt="" class="w-5 h-auto rounded-sm">
                                {{ strtoupper($code) }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('page_header')
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-primary dark:bg-dark-card text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                {{-- Col 1: About --}}
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ asset('images/aseanapol-logo.png') }}" alt="ASEANAPOL" class="h-12 w-auto">
                        <div>
                            <div class="font-bold text-lg tracking-wide">ASEANAPOL</div>
                            <div class="text-accent text-xs tracking-widest uppercase">Secretariat</div>
                        </div>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-6">
                        {{ __('landing.footer_desc') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="https://twitter.com/ASEANAPOL1981" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-accent hover:text-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/aseanapol" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-accent hover:text-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/ASEANAPOL" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-accent hover:text-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/in/aseanapol-secretariat-344498ab" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-accent hover:text-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Col 2: Quick Links --}}
                <div>
                    <h4 class="font-semibold text-lg mb-5">{{ __('landing.footer_quick_links') }}</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> About ASEANAPOL</a></li>
                        <li><a href="{{ route('about.permanent-secretariat', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Permanent Secretariat</a></li>
                        <li><a href="{{ route('about.governance', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Governance</a></li>
                        <li><a href="{{ route('about.chronology', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> History</a></li>
                        <li><a href="{{ route('about.vision-mission', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Vision &amp; Mission</a></li>
                        <li><a href="{{ route('about.member-countries', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Member Countries</a></li>
                        <li><a href="{{ route('about.dialogue-partners', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Dialogue Partners</a></li>
                        <li><a href="{{ route('international-cooperation', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> International Cooperation</a></li>
                        <li><a href="{{ route('news.index', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> News</a></li>
                        <li><a href="{{ route('data-resources.publications', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Publications</a></li>
                        <li><a href="{{ route('contact-us', ['locale' => app()->getLocale()]) }}" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Contact Us</a></li>
                    </ul>
                </div>

                {{-- Col 3: Partners & Resources --}}
                <div>
                    <h4 class="font-semibold text-lg mb-5">{{ __('landing.footer_partners_label') }}</h4>
                    <ul class="space-y-3 mb-8">
                        <li><a href="https://www.interpol.int" target="_blank" rel="noopener" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> INTERPOL</a></li>
                        <li><a href="https://www.europol.europa.eu" target="_blank" rel="noopener" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> Europol</a></li>
                        <li><a href="https://www.unodc.org" target="_blank" rel="noopener" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> UNODC</a></li>
                        <li><a href="#" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> ACCBP</a></li>
                    </ul>
                    <h4 class="font-semibold text-lg mb-5">{{ __('landing.footer_resources') }}</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> e-ADS</a></li>
                        <li><a href="#" class="text-white/70 hover:text-accent text-sm transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-base">chevron_right</span> ASEANAPOL Database</a></li>
                    </ul>
                </div>

                {{-- Col 4: Contact --}}
                <div>
                    <h4 class="font-semibold text-lg mb-5">{{ __('landing.footer_contact') }}</h4>
                    <div class="space-y-4 text-sm text-white/70">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">location_on</span>
                            <p>Level 13, Tower 2, Bank Rakyat Twin Tower, No 33, Jalan Rakyat, 50470 Kuala Lumpur, Malaysia</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">phone</span>
                            <p>+603-22602222</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">fax</span>
                            <p>+603-22602205</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">mail</span>
                            <a href="mailto:aseanapolsec@aseanapol.org" class="hover:text-accent transition-colors">aseanapolsec@aseanapol.org</a>
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="mt-6">
                        <h5 class="font-medium text-sm mb-3">{{ __('landing.footer_stay_updated') }}</h5>
                        <form class="flex gap-2" onsubmit="return false;">
                            <input type="email" placeholder="{{ __('landing.footer_your_email') }}" class="flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-sm text-white placeholder-white/50 outline-none focus:border-accent transition-colors">
                            <button type="submit" class="bg-accent hover:bg-accent-600 text-primary font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-white/50 text-sm">{!! __('landing.footer_copyright') !!}</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-white/50 hover:text-accent text-sm transition-colors">{{ __('landing.footer_privacy') }}</a>
                    <a href="#" class="text-white/50 hover:text-accent text-sm transition-colors">{{ __('landing.footer_terms') }}</a>
                    <a href="#" class="text-white/50 hover:text-accent text-sm transition-colors">{{ __('landing.footer_sitemap') }}</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to Top Button --}}
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-accent hover:bg-accent-600 text-primary rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
        <span class="material-symbols-outlined text-2xl">keyboard_arrow_up</span>
    </button>

    <script>
        // Sticky header shadow on scroll
        const header = document.getElementById('main-header');
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.classList.add('shadow-lg', 'shadow-black/20');
            } else {
                header.classList.remove('shadow-lg', 'shadow-black/20');
            }
            // Back to top visibility
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('open');
            icon.textContent = menu.classList.contains('open') ? 'close' : 'menu';
        }

        // Mobile accordion sub-menus
        function toggleMobSub(id) {
            const el = document.getElementById(id);
            el.classList.toggle('open');
        }

        // Dropdown toggle
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            el.classList.toggle('show');
        }
        // Close dropdowns on outside click
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.dropdown-menu.show').forEach(d => {
                if (!d.parentElement.contains(e.target)) d.classList.remove('show');
            });
        });

        // Dark mode toggle
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon();
        }
        function updateThemeIcon() {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.textContent = document.documentElement.classList.contains('dark') ? 'light_mode' : 'dark_mode';
            }
        }
        updateThemeIcon();
    </script>
    @stack('scripts')
</body>
</html>
