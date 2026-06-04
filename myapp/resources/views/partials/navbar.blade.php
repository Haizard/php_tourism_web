@php
    $locale = $currentLocale ?? app()->getLocale();
    $navItems = [
        ['label' => 'Home', 'url' => url("/{$locale}"), 'icon' => 'heroicon-o-home'],
        ['label' => 'Tours', 'url' => url("/{$locale}/tours"), 'icon' => 'heroicon-o-map-pin'],
        ['label' => 'Destinations', 'url' => url("/{$locale}/destinations"), 'icon' => 'heroicon-o-globe-alt'],
        ['label' => 'Blog', 'url' => url("/{$locale}/blog"), 'icon' => 'heroicon-o-newspaper'],
        ['label' => 'Contact', 'url' => url("/{$locale}/contact"), 'icon' => 'heroicon-o-envelope'],
    ];

    $currentPath = request()->path();
@endphp

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/40 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-3">
            @foreach ($navItems as $item)
                @php
                    $isActive = str_contains($currentPath, trim(parse_url($item['url'], PHP_URL_PATH), '/'));
                @endphp
                <a href="{{ $item['url'] }}"
                   class="navbar-pill {{ $isActive ? 'navbar-pill-active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
