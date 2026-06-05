@php
    $locale = $currentLocale ?? app()->getLocale();
    $currentPath = request()->path();

    $staticItems = [
        ['label' => 'Home',    'url' => url("/{$locale}"),          'type' => 'static'],
        ['label' => 'Blog',    'url' => url("/{$locale}/blog"),      'type' => 'static'],
        ['label' => 'Contact', 'url' => url("/{$locale}/contact"),   'type' => 'static'],
    ];
@endphp

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/40 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-3">

            {{-- Home pill --}}
            @php $isActive = str_contains($currentPath, trim(parse_url(url("/{$locale}"), PHP_URL_PATH), '/')); @endphp
            <a href="{{ url("/{$locale}") }}"
               class="navbar-pill {{ $isActive ? 'navbar-pill-active' : '' }}">
                Home
            </a>

            {{-- Dynamic navbar items from admin --}}
            @foreach ($navbarItems ?? [] as $item)
                @php
                    $resolvedLabel = $item->label ?: (
                        $item->type === 'category'
                            ? optional($item->category)->name
                            : (
                                $item->type === 'destination'
                                    ? optional($item->destination)->name
                                    : 'Link'
                            )
                    );

                    $hasDropdown = in_array($item->type, ['category', 'destination'])
                        && $item->dropdownTours->isNotEmpty();

                    $itemUrl = $item->url ?: '#';
                    $isItemActive = $item->url && str_contains($currentPath, trim(parse_url($item->url, PHP_URL_PATH), '/'));
                @endphp

                @if ($hasDropdown)
                    <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                        <button
                            @click="open = !open"
                            class="navbar-pill {{ $isItemActive ? 'navbar-pill-active' : '' }} inline-flex items-center gap-1.5"
                        >
                            {{ $resolvedLabel }}
                            <svg x-bind:class="open ? 'rotate-180' : ''" class="h-3.5 w-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                            class="absolute left-0 top-full pt-2 w-64 z-50"
                            x-cloak
                        >
                            <div class="rounded-2xl border border-slate-200/60 bg-white/95 backdrop-blur-xl shadow-xl shadow-slate-900/10 py-2 overflow-hidden">
                                @foreach ($item->dropdownTours as $tour)
                                    <a href="{{ url("/{$locale}/tours/{$tour->slug}") }}"
                                       class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[var(--color-primary)]/8 hover:text-[var(--color-primary)] transition-colors duration-150">
                                        {{ $tour->title }}
                                    </a>
                                @endforeach
                                @if ($item->type === 'category')
                                    <div class="mx-4 my-1 border-t border-slate-100"></div>
                                    <a href="{{ url("/{$locale}/tours") }}"
                                       class="block px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[var(--color-accent)] hover:bg-[var(--color-accent)]/8 transition-colors duration-150">
                                        View all tours →
                                    </a>
                                @elseif ($item->type === 'destination')
                                    <div class="mx-4 my-1 border-t border-slate-100"></div>
                                    <a href="{{ url("/{$locale}/destinations") }}"
                                       class="block px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[var(--color-accent)] hover:bg-[var(--color-accent)]/8 transition-colors duration-150">
                                        View all destinations →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $itemUrl }}"
                       {{ $item->open_in_new_tab ? 'target=_blank rel=noopener' : '' }}
                       class="navbar-pill {{ $isItemActive ? 'navbar-pill-active' : '' }}">
                        {{ $resolvedLabel }}
                    </a>
                @endif
            @endforeach

            {{-- Blog & Contact always visible --}}
            @php
                $blogActive = str_contains($currentPath, "{$locale}/blog");
                $contactActive = str_contains($currentPath, "{$locale}/contact");
            @endphp
            <a href="{{ url("/{$locale}/blog") }}"
               class="navbar-pill {{ $blogActive ? 'navbar-pill-active' : '' }}">
                Blog
            </a>
            <a href="{{ url("/{$locale}/contact") }}"
               class="navbar-pill {{ $contactActive ? 'navbar-pill-active' : '' }}">
                Contact
            </a>

        </div>
    </div>
</nav>
