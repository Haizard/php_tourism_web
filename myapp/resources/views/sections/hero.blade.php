@php
    $bg = $sectionBackgrounds['hero'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $sectionTextClass = $bg ? $bg->text_class : 'text-slate-950';
    $locale = $currentLocale ?? app()->getLocale();

    try {
        $heroCategories = \App\Models\Category::orderBy('name')->get();
    } catch (\Exception $e) {
        $heroCategories = collect();
    }
    try {
        $heroDurations = \App\Models\Tour::where('is_published', true)
            ->whereNotNull('duration')
            ->distinct()->pluck('duration')->sort()->values();
    } catch (\Exception $e) {
        $heroDurations = collect();
    }
    try {
        $heroPriceMax = (int) (\App\Models\Tour::where('is_published', true)->max('price') ?? 10000);
    } catch (\Exception $e) {
        $heroPriceMax = 10000;
    }
@endphp

<section class="page-hero {{ $hasBg ? '' : 'page-hero--hero1' }} relative overflow-hidden {{ $sectionTextClass }}" style="{{ $bgStyle }}">
    @if (!$hasBg)
        <div class="page-hero__image absolute inset-0"></div>
    @endif
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @elseif (!$hasBg)
        <div class="page-hero__overlay absolute inset-0 bg-slate-950/75"></div>
    @endif

    <div class="relative mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
        {{-- Hero headline + image stack --}}
        <div class="grid gap-10 lg:grid-cols-[1.12fr_0.88fr] lg:items-center">
            <div class="max-w-2xl text-white">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-pink-200 shadow-[0_20px_80px_rgba(236,72,153,0.08)]">
                    Ticket Plan
                </span>
                <h1 class="mt-8 text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">Explore Beautiful World With Us</h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-100/90">"Let us take the hassle out of travel planning, so you can focus on the adventure ahead."</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ url("/{$locale}/tours") }}"
                       class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[var(--color-primary)] via-[var(--color-accent)] to-[var(--color-primary)] px-7 py-3 text-sm font-semibold text-white shadow-2xl shadow-[rgba(140,92,246,0.28)] transition hover:-translate-y-0.5">
                        Discover Now
                    </a>
                    <a href="{{ url("/{$locale}/contact") }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                        Book a Trip
                    </a>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-[1.32fr_1fr] sm:grid-rows-[280px_200px]">
                <article class="hero-stack-card sm:row-span-2">
                    <img src="{{ asset('images/creation-africa/hero1.jpg') }}" alt="Beautiful bridge" loading="lazy" />
                    <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                        <p class="text-sm uppercase tracking-[0.35em] text-pink-100">Featured</p>
                        <h2 class="mt-4 text-2xl font-black">Paris City Lights</h2>
                        <p class="mt-3 text-sm text-slate-100/85">7 days · Luxury · Breakfast included</p>
                    </div>
                </article>
                <article class="hero-stack-card">
                    <img src="{{ asset('images/creation-africa/safari.jpg') }}" alt="Taj Mahal travel" loading="lazy" />
                    <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                        <p class="text-sm uppercase tracking-[0.35em] text-pink-100">Top choice</p>
                        <h3 class="mt-3 text-xl font-black">Taj Mahal</h3>
                    </div>
                </article>
                <article class="hero-stack-card">
                    <img src="{{ asset('images/creation-africa/cheetah.jpg') }}" alt="Istanbul travel" loading="lazy" />
                    <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                        <p class="text-sm uppercase tracking-[0.35em] text-pink-100">Exclusive</p>
                        <h3 class="mt-3 text-xl font-black">Istanbul</h3>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

{{-- Tour Search Bar - Below Header --}}
<section class="relative bg-gradient-to-b from-slate-50 to-white py-8 lg:py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <form action="{{ url("/{$locale}/tours") }}" method="GET"
              x-data="tourSearch()"
              class="relative rounded-2xl border border-slate-200 bg-white shadow-lg overflow-hidden">

            {{-- Tab bar --}}
            <div class="flex border-b border-slate-200">
                <button type="button" @click="tab='tours'"
                        :class="tab==='tours' ? 'bg-slate-100 text-slate-950 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50'"
                        class="px-6 py-3.5 text-sm font-semibold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Find a Tour
                </button>
                <button type="button" @click="tab='destination'"
                        :class="tab==='destination' ? 'bg-slate-100 text-slate-950 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50'"
                        class="px-6 py-3.5 text-sm font-semibold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    By Destination
                </button>
            </div>

            {{-- Main filter row --}}
            <div class="grid grid-cols-1 gap-px sm:grid-cols-2 lg:grid-cols-5 bg-slate-100">

                {{-- Keyword --}}
                <div class="bg-white px-5 py-4 hover:bg-slate-50 transition col-span-1 lg:col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-1">
                        Search
                    </label>
                    <input
                        type="text"
                        name="q"
                        placeholder="Tour name, keyword…"
                        class="w-full bg-transparent text-sm font-semibold text-slate-950 placeholder-slate-400 focus:outline-none"
                    >
                </div>

                {{-- Category --}}
                <div class="bg-white px-5 py-4 hover:bg-slate-50 transition">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-1">Category</label>
                    <select name="category"
                            class="w-full bg-transparent text-sm font-semibold text-slate-950 focus:outline-none appearance-none cursor-pointer">
                        <option value="" class="text-slate-900">All categories</option>
                        @foreach($heroCategories as $cat)
                            <option value="{{ $cat->slug }}" class="text-slate-900">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Duration --}}
                <div class="bg-white px-5 py-4 hover:bg-slate-50 transition">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-1">Duration</label>
                    <select name="duration"
                            class="w-full bg-transparent text-sm font-semibold text-slate-950 focus:outline-none appearance-none cursor-pointer">
                        <option value="" class="text-slate-900">Any duration</option>
                        <option value="1" class="text-slate-900">1–3 Days</option>
                        <option value="4" class="text-slate-900">4–6 Days</option>
                        <option value="7" class="text-slate-900">7–10 Days</option>
                        <option value="11" class="text-slate-900">11–14 Days</option>
                        <option value="15" class="text-slate-900">15+ Days</option>
                        @foreach($heroDurations->filter(fn($d) => !in_array($d, ['1','4','7','11','15'])) as $dur)
                            <option value="{{ $dur }}" class="text-slate-900">{{ $dur }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Max price + submit --}}
                <div class="bg-white px-5 py-4 hover:bg-slate-50 transition flex flex-col justify-between">
                    <div>
                        <label class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-1">
                            <span>Max price</span>
                            <span class="text-slate-950 font-bold" x-text="'$' + maxPrice.toLocaleString()"></span>
                        </label>
                        <input
                            type="range"
                            name="max_price"
                            min="0"
                            :max="{{ $heroPriceMax }}"
                            step="100"
                            x-model.number="maxPrice"
                            class="w-full h-1.5 rounded-full appearance-none cursor-pointer accent-[var(--color-accent)]"
                        >
                    </div>
                </div>
            </div>

            {{-- Search button --}}
            <div class="flex items-center justify-between gap-4 px-5 py-4 bg-slate-50">
                <p class="text-xs text-slate-500 hidden sm:block">
                    Press Search to browse matching tours — filters can be combined.
                </p>
                <button type="submit"
                        class="ml-auto inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] px-8 py-3 text-sm font-bold text-white shadow-lg transition hover:opacity-90 hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    Search Tours
                </button>
            </div>
        </form>
    </div>
</section>

<script>
function tourSearch() {
    return {
        tab: 'tours',
        maxPrice: {{ $heroPriceMax }},
    }
}
</script>
