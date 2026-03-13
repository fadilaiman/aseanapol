<section class="bg-primary dark:bg-dark-card pt-24 pb-12 relative overflow-hidden">
    {{-- Subtle pattern overlay --}}
    <div class="absolute inset-0 opacity-5" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap gap-1 text-sm text-white/60 mb-5">
            @foreach($breadcrumbs as $crumb)
                @if(!$loop->last)
                    <a href="{{ $crumb['url'] }}" class="hover:text-accent transition-colors">{{ $crumb['label'] }}</a>
                    <span class="material-symbols-outlined text-sm leading-none">chevron_right</span>
                @else
                    <span class="text-white/90">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </nav>

        {{-- Title --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
            {{ $title }}
        </h1>

        @if(isset($subtitle))
            <p class="text-white/70 mt-3 text-lg max-w-2xl leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>
</section>
