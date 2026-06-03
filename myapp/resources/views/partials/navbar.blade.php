@php
    $locale = $currentLocale ?? app()->getLocale();
    $navItems = [
        ['label' => 'Home', 'url' => url("/{$locale}")],
        ['label' => 'Tours', 'url' => url("/{$locale}/tours")],
        ['label' => 'Destinations', 'url' => url("/{$locale}/destinations")],
        ['label' => 'Blog', 'url' => url("/{$locale}/blog")],
        ['label' => 'Contact', 'url' => url("/{$locale}/contact")],
    ];
@endphp

<nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
    <a href="{{ url("/{$locale}") }}" class="text-lg font-black tracking-tight text-slate-950">
        Tourism Starter Kit
    </a>

    <div class="hidden items-center gap-6 md:flex">
        @foreach ($navItems as $item)
            <a href="{{ $item['url'] }}" class="text-sm font-semibold text-slate-700 transition hover:text-[var(--color-primary)]">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <a href="{{ url("/{$locale}/contact") }}" class="rounded-full bg-[var(--color-primary)] px-5 py-2 text-sm font-bold text-white shadow-lg shadow-cyan-900/10 transition hover:-translate-y-0.5">
        Plan a Trip
    </a>
</nav>
