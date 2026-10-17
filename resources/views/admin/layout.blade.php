<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>ASEANAPOL Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">
    <a href="#admin-main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:text-blue-700 focus:px-3 focus:py-2">Skip to content</a>
    <div class="flex h-screen overflow-hidden">
        {{-- mobile drawer backdrop --}}
        <div id="admin-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" aria-hidden="true"></div>

        {{-- Sidebar --}}
        <aside id="admin-sidebar" aria-label="Admin navigation" class="fixed md:static inset-y-0 left-0 z-40 w-60 flex-shrink-0 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200" style="background:#0a2540;">
            <div class="px-6 py-5 border-b border-white/10">
                <div class="font-bold text-white text-base tracking-wide">ASEANAPOL</div>
                <div class="text-white/40 text-xs mt-0.5">Admin Panel</div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',          'label' => 'Dashboard',      'icon' => 'dashboard',    'match' => 'admin.dashboard'],
                        ['route' => 'admin.cms.index',          'label' => 'Site Content',   'icon' => 'account_tree', 'match' => 'admin.cms.*'],
                        ['route' => 'admin.video-gallery.index','label' => 'Video Gallery',   'icon' => 'play_circle',  'match' => 'admin.video-gallery.*'],
                        ['route' => 'admin.newsletters.index',  'label' => 'Newsletters',     'icon' => 'mail',         'match' => 'admin.newsletters.*'],
                        ['route' => 'admin.digital-library.items.index', 'label' => 'Digital Library', 'icon' => 'library_books', 'match' => 'admin.digital-library.*'],
                        ['route' => 'admin.news.index',                  'label' => 'News',            'icon' => 'newspaper',     'match' => 'admin.news.*'],
                        ['route' => 'admin.events.index',                'label' => 'Events',          'icon' => 'event',         'match' => 'admin.events.*'],
                        ['route' => 'admin.publications.index',          'label' => 'Publications',    'icon' => 'menu_book',     'match' => 'admin.publications.*'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    @continue(!Route::has($item['route']))
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                              {{ $active ? 'bg-white/15 text-white font-semibold' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined" style="font-size:20px;">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 pb-4 pt-2 border-t border-white/10">
                <div class="text-white/40 text-xs px-3 mb-2 truncate">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-white/65 hover:bg-white/10 hover:text-white w-full transition-colors">
                        <span class="material-symbols-outlined" style="font-size:20px;">logout</span>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main area --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 flex-shrink-0 flex items-center gap-3">
                <button type="button" id="admin-menu-btn" class="md:hidden p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100" aria-label="Open navigation menu" aria-controls="admin-sidebar" aria-expanded="false">
                    <span class="material-symbols-outlined" aria-hidden="true">menu</span>
                </button>
                <h1 class="text-gray-800 font-semibold text-base truncate">@yield('title', 'Dashboard')</h1>
            </header>

            <main id="admin-main" tabindex="-1" class="flex-1 overflow-y-auto p-4 md:p-6">
                @if(session('success'))
                    <div role="status" class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
                        <span class="material-symbols-outlined text-green-600" style="font-size:18px;">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div role="alert" class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">
                        <span class="material-symbols-outlined text-red-600" style="font-size:18px;">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>
    <script>
    (function () {
        var sb = document.getElementById('admin-sidebar'), bd = document.getElementById('admin-backdrop'), btn = document.getElementById('admin-menu-btn');
        function set(open) {
            sb.classList.toggle('-translate-x-full', !open);
            bd.classList.toggle('hidden', !open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) { var a = sb.querySelector('a'); if (a) a.focus(); } else { btn.focus(); }
        }
        btn.addEventListener('click', function () { set(sb.classList.contains('-translate-x-full')); });
        bd.addEventListener('click', function () { set(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !sb.classList.contains('-translate-x-full') && window.innerWidth < 768) set(false); });
    })();
    </script>
</body>
</html>
