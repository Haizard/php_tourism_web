<x-layouts.app :title="'Tour Packages | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Explore curated travel experiences powered by our admin-driven destination platform.'">
    <x-page-header
        eyebrow="Tour packages"
        title="Curated journeys for every traveler"
        subtitle="From family safaris to luxury retreats, every itinerary is built with thoughtful experiences and local expertise."
        image="{{ asset('images/creation-africa/tanzania-lodge-safaris.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Plan a safari"
    />

    <section class="px-6 py-12 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- ─── Inline search / filter bar ─── --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-md overflow-hidden"
                 x-data="{ showFilters: {{ ($q || $category || $duration || $maxPrice) ? 'true' : 'false' }} }">

                <form action="{{ url("/{$currentLocale}/tours") }}" method="GET">
                    {{-- Main keyword row --}}
                    <div class="flex flex-col sm:flex-row gap-0">
                        <div class="flex flex-1 items-center gap-3 border-b sm:border-b-0 sm:border-r border-slate-200 px-5 py-4">
                            <svg class="h-5 w-5 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                            <input type="text" name="q" value="{{ $q }}"
                                   placeholder="Search tours by name or keyword…"
                                   class="flex-1 bg-transparent text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none">
                        </div>
                        <div class="flex items-center gap-2 px-4 py-3">
                            <button type="button" @click="showFilters = !showFilters"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                                </svg>
                                Filters
                                @if($category || $duration || $maxPrice)
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[var(--color-accent)] text-[10px] font-bold text-white">
                                        {{ (int)($category !== '') + (int)($duration !== '') + (int)($maxPrice !== '') }}
                                    </span>
                                @endif
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-primary)] px-5 py-2 text-sm font-bold text-white transition hover:opacity-90">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                                Search
                            </button>
                        </div>
                    </div>

                    {{-- Expandable filter row --}}
                    <div x-show="showFilters"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="border-t border-slate-200 bg-slate-50/60 px-5 py-4"
                         style="display: {{ ($q || $category || $duration || $maxPrice) ? 'block' : 'none' }}">
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                            {{-- Category --}}
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">Category</label>
                                <select name="category"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:outline-none">
                                    <option value="">All categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->slug }}" {{ $category === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Duration --}}
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">Duration</label>
                                <select name="duration"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:outline-none">
                                    <option value="">Any duration</option>
                                    <option value="1" {{ str_contains($duration,'1') ? 'selected' : '' }}>1–3 Days</option>
                                    <option value="4" {{ str_contains($duration,'4') ? 'selected' : '' }}>4–6 Days</option>
                                    <option value="7" {{ str_contains($duration,'7') ? 'selected' : '' }}>7–10 Days</option>
                                    <option value="11" {{ str_contains($duration,'11') ? 'selected' : '' }}>11–14 Days</option>
                                    <option value="15" {{ str_contains($duration,'15') ? 'selected' : '' }}>15+ Days</option>
                                    @foreach($durations->filter(fn($d) => !in_array($d, ['1','4','7','11','15'])) as $dur)
                                        <option value="{{ $dur }}" {{ $duration === $dur ? 'selected' : '' }}>{{ $dur }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Max price --}}
                            <div x-data="{ maxP: {{ $maxPrice ?: ($priceRange->max_p ?? 10000) }} }">
                                <label class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">
                                    <span>Max price</span>
                                    <span class="text-[var(--color-accent)] font-bold" x-text="'$' + maxP.toLocaleString()"></span>
                                </label>
                                <input type="range" name="max_price"
                                       min="0"
                                       max="{{ $priceRange->max_p ?? 10000 }}"
                                       step="100"
                                       x-model.number="maxP"
                                       value="{{ $maxPrice ?: ($priceRange->max_p ?? 10000) }}"
                                       class="w-full h-1.5 rounded-full appearance-none cursor-pointer accent-[var(--color-accent)]">
                            </div>

                            {{-- Clear all --}}
                            <div class="flex items-end">
                                <a href="{{ url("/{$currentLocale}/tours") }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-red-200 hover:text-red-600 hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear filters
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ─── Active filter tags ─── --}}
            @if($q || $category || $duration || $maxPrice)
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-semibold text-slate-500">Active:</span>
                    @if($q)
                        <a href="{{ url("/{$currentLocale}/tours?" . http_build_query(array_filter(['category'=>$category,'duration'=>$duration,'max_price'=>$maxPrice]))) }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20 px-3 py-1 text-xs font-bold text-[var(--color-primary)] hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">
                            "{{ $q }}"
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if($category)
                        @php $catLabel = $categories->firstWhere('slug', $category)?->name ?? $category; @endphp
                        <a href="{{ url("/{$currentLocale}/tours?" . http_build_query(array_filter(['q'=>$q,'duration'=>$duration,'max_price'=>$maxPrice]))) }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-accent)]/10 border border-[var(--color-accent)]/20 px-3 py-1 text-xs font-bold text-[var(--color-accent)] hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">
                            {{ $catLabel }}
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if($duration)
                        <a href="{{ url("/{$currentLocale}/tours?" . http_build_query(array_filter(['q'=>$q,'category'=>$category,'max_price'=>$maxPrice]))) }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-bold text-slate-600 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">
                            {{ $duration }} days
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if($maxPrice)
                        <a href="{{ url("/{$currentLocale}/tours?" . http_build_query(array_filter(['q'=>$q,'category'=>$category,'duration'=>$duration]))) }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-green-50 border border-green-200 px-3 py-1 text-xs font-bold text-green-700 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">
                            Under ${{ number_format((int)$maxPrice) }}
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    <span class="text-xs text-slate-400">{{ $tours->count() }} {{ Str::plural('result', $tours->count()) }}</span>
                </div>
            @endif

            {{-- ─── Tours grid ─── --}}
            @if($tours->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($tours as $tour)
                        @php
                            $displayPrice = ($tour->discount_price && $tour->discount_price < $tour->price)
                                ? $tour->discount_price : $tour->price;
                            $hasDiscount  = $tour->discount_price && $tour->discount_price < $tour->price;
                        @endphp
                        <a href="{{ route('tours.show', ['locale' => $currentLocale, 'slug' => $tour->slug]) }}"
                           class="group rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-md shadow-slate-200/40 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60">

                            {{-- Image --}}
                            @if($tour->featured_image)
                                <div class="relative h-52 overflow-hidden bg-slate-100">
                                    <img src="{{ asset('storage/' . $tour->featured_image) }}"
                                         alt="{{ $tour->title }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy">
                                    @if($tour->category)
                                        <span class="absolute top-3 left-3 rounded-full bg-black/40 backdrop-blur-sm px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white">
                                            {{ $tour->category->name }}
                                        </span>
                                    @endif
                                    @if($hasDiscount)
                                        <span class="absolute top-3 right-3 rounded-full bg-red-500 px-2.5 py-1 text-[10px] font-bold text-white">SALE</span>
                                    @endif
                                </div>
                            @else
                                <div class="relative flex h-52 items-center justify-center bg-gradient-to-br from-[var(--color-primary)]/10 to-[var(--color-accent)]/10">
                                    <svg class="h-14 w-14 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @if($tour->category)
                                        <span class="absolute top-3 left-3 rounded-full bg-[var(--color-accent)]/15 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[var(--color-accent)]">
                                            {{ $tour->category->name }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="p-6">
                                <h2 class="text-lg font-bold text-slate-950 leading-snug group-hover:text-[var(--color-accent)] transition-colors line-clamp-2">
                                    {{ $tour->title }}
                                </h2>

                                @if($tour->duration)
                                    <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                                        </svg>
                                        {{ $tour->duration }}
                                    </p>
                                @endif

                                @if($tour->excerpt)
                                    <p class="mt-3 text-sm leading-6 text-slate-600 line-clamp-2">{{ $tour->excerpt }}</p>
                                @endif

                                <div class="mt-5 flex items-center justify-between gap-3">
                                    @if($tour->price)
                                        <div>
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-2xl font-black text-[var(--color-primary)]">${{ number_format($displayPrice, 0) }}</span>
                                                @if($hasDiscount)
                                                    <span class="text-sm text-slate-400 line-through">${{ number_format($tour->price, 0) }}</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-400">per person</p>
                                        </div>
                                    @endif
                                    <span class="inline-flex items-center gap-1 rounded-full bg-[var(--color-accent)] px-4 py-2 text-sm font-bold text-white transition group-hover:bg-[var(--color-primary)]">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-16 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-slate-800">No tours found</p>
                    <p class="mt-2 text-sm text-slate-500">Try adjusting your filters or clearing the search.</p>
                    <a href="{{ url("/{$currentLocale}/tours") }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-full bg-[var(--color-primary)] px-6 py-2.5 text-sm font-bold text-white transition hover:opacity-90">
                        Clear all filters
                    </a>
                </div>
            @endif

        </div>
    </section>
</x-layouts.app>
