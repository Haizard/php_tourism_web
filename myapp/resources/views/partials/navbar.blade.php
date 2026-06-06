@php
    use App\Models\Destination;

    $locale = $currentLocale ?? app()->getLocale();
    $currentPath = request()->path();

    $builtItems = $navItems->map(function ($item) use ($locale, $currentPath) {
        $dropdown = [];

        if ($item->type === 'manual') {
            $rawUrl = $item->url ?? '/';
            if (str_starts_with($rawUrl, 'http')) {
                $url = $rawUrl;
            } else {
                $url = url('/' . $locale . '/' . ltrim($rawUrl, '/'));
                if (rtrim($url, '/') === rtrim(url('/' . $locale . '/'), '/')) {
                    $url = url('/' . $locale);
                }
            }
            foreach ($item->children as $child) {
                $childRaw = $child->url ?? '/';
                $childUrl = str_starts_with($childRaw, 'http') ? $childRaw : url('/' . $locale . '/' . ltrim($childRaw, '/'));
                $dropdown[] = ['label' => $child->label, 'url' => $childUrl];
            }
        } elseif ($item->type === 'category' && $item->category) {
            $url = url("/{$locale}/tours") . '?category=' . $item->category->slug;
            foreach ($item->category->tours as $tour) {
                $dropdown[] = ['label' => $tour->title, 'url' => url("/{$locale}/tours/{$tour->slug}")];
            }
        } elseif ($item->type === 'destinations_hub') {
            $url = url("/{$locale}/destinations");
            try {
                $destinations = Destination::where('is_published', true)->orderBy('name')->get();
                foreach ($destinations as $dest) {
                    $dropdown[] = ['label' => $dest->name, 'url' => url("/{$locale}/destinations/{$dest->slug}")];
                }
            } catch (\Exception $e) {
                $destinations = collect();
            }
        } else {
            $url = '#';
        }

        $label = $item->label
            ?: ($item->type === 'category' ? ($item->category?->name ?? 'Category') : 'Destinations');

        $isActive = $url !== '#' && str_contains('/' . $currentPath, parse_url($url, PHP_URL_PATH) ?? '');

        return compact('label', 'url', 'dropdown', 'isActive', 'item');
    });
@endphp

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/40 shadow-sm"
     x-data="{ mobileOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 lg:justify-center lg:gap-2 lg:flex-wrap">

            {{-- Mobile: site name / logo on the left --}}
            <a href="{{ url('/' . $locale) }}"
               class="flex items-center gap-2 font-bold text-slate-900 text-lg lg:hidden">
                @php $generalSettings = app(\App\Settings\GeneralSettings::class); @endphp
                @if($generalSettings->logo)
                    <img src="{{ asset('storage/' . $generalSettings->logo) }}" alt="{{ $generalSettings->siteName }}" class="h-8 w-auto object-contain">
                @else
                    {{ $generalSettings->siteName }}
                @endif
            </a>

            {{-- Desktop nav pills --}}
            <div class="hidden lg:flex flex-wrap items-center justify-center gap-2">
                @foreach ($builtItems as $navData)
                    @if (count($navData['dropdown']) > 0)
                        {{-- Dropdown item --}}
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button
                                @click="open = !open"
                                class="navbar-pill {{ $navData['isActive'] ? 'navbar-pill-active' : '' }} inline-flex items-center gap-1"
                            >
                                {{ $navData['label'] }}
                                <svg class="h-3.5 w-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 top-full z-50 mt-1 min-w-[200px] rounded-2xl border border-slate-200/60 bg-white/95 shadow-xl shadow-slate-900/10 backdrop-blur-xl overflow-hidden"
                                style="display: none;"
                            >
                                <div class="py-2">
                                    <a href="{{ $navData['url'] }}"
                                       class="block px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-primary)] hover:bg-slate-50">
                                        View All
                                    </a>
                                    <div class="my-1 border-t border-slate-100"></div>
                                    @foreach ($navData['dropdown'] as $child)
                                        <a href="{{ $child['url'] }}"
                                           class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-[var(--color-primary)] transition-colors">
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $navData['url'] }}"
                           @if($navData['item']->open_in_new_tab) target="_blank" rel="noopener" @endif
                           class="navbar-pill {{ $navData['isActive'] ? 'navbar-pill-active' : '' }}">
                            {{ $navData['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Mobile: hamburger button --}}
            <button
                @click="mobileOpen = !mobileOpen"
                class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white/80 text-slate-700 hover:bg-slate-50 transition-colors"
                aria-label="Toggle menu">
                <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile nav drawer --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-slate-100 pb-4"
            style="display: none;"
            @click.outside="mobileOpen = false">
            <div class="space-y-1 pt-3">
                @foreach ($builtItems as $navData)
                    @if (count($navData['dropdown']) > 0)
                        <div x-data="{ subOpen: false }">
                            <button
                                @click="subOpen = !subOpen"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-colors
                                    {{ $navData['isActive'] ? 'bg-[var(--color-accent)]/10 text-[var(--color-accent)]' : 'text-slate-700 hover:bg-slate-50' }}">
                                {{ $navData['label'] }}
                                <svg class="w-4 h-4 transition-transform" :class="subOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="subOpen" class="mt-1 ml-4 space-y-1 border-l-2 border-slate-100 pl-3" style="display:none;">
                                <a href="{{ $navData['url'] }}"
                                   class="block px-3 py-2 text-xs font-bold uppercase tracking-widest text-[var(--color-primary)]">
                                    View All
                                </a>
                                @foreach ($navData['dropdown'] as $child)
                                    <a href="{{ $child['url'] }}"
                                       class="block px-3 py-2 text-sm text-slate-600 hover:text-[var(--color-primary)] transition-colors">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $navData['url'] }}"
                           @if($navData['item']->open_in_new_tab) target="_blank" rel="noopener" @endif
                           @click="mobileOpen = false"
                           class="block px-4 py-3 rounded-xl text-sm font-semibold transition-colors
                               {{ $navData['isActive'] ? 'bg-[var(--color-accent)]/10 text-[var(--color-accent)]' : 'text-slate-700 hover:bg-slate-50' }}">
                            {{ $navData['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</nav>
