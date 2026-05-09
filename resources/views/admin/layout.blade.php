<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEANAPOL Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-60 flex-shrink-0 flex flex-col" style="background:#0a2540;">
            <div class="px-6 py-5 border-b border-white/10">
                <div class="font-bold text-white text-base tracking-wide">ASEANAPOL</div>
                <div class="text-white/40 text-xs mt-0.5">Admin Panel</div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',          'label' => 'Dashboard',      'icon' => 'dashboard',    'match' => 'admin.dashboard'],
                        ['route' => 'admin.video-gallery.index','label' => 'Video Gallery',   'icon' => 'play_circle',  'match' => 'admin.video-gallery.*'],
                        ['route' => 'admin.newsletters.index',  'label' => 'Newsletters',     'icon' => 'mail',         'match' => 'admin.newsletters.*'],
                        ['route' => 'admin.digital-library.items.index', 'label' => 'Digital Library', 'icon' => 'library_books', 'match' => 'admin.digital-library.*'],
                    ];
                @endphp
                @foreach($navItems as $item)
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
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex-shrink-0">
                <h1 class="text-gray-800 font-semibold text-base">@yield('title', 'Dashboard')</h1>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
                        <span class="material-symbols-outlined text-green-600" style="font-size:18px;">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">
                        <span class="material-symbols-outlined text-red-600" style="font-size:18px;">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>
