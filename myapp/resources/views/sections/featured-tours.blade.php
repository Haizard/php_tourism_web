@php
    $bg = $sectionBackgrounds['featured_tours'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $sectionTextClass = $bg ? $bg->text_class : 'text-slate-950';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
    $mutedClass   = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
    $locale = $currentLocale ?? app()->getLocale();
@endphp

<section class="px-6 py-16 lg:px-8 relative {{ $sectionTextClass }}" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header text-center">
            <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Featured Tours</span>
            <h2 class="mt-4 text-4xl font-black {{ $headingClass }}">Handpicked experiences for you</h2>
            <p class="mt-3 mx-auto max-w-2xl {{ $mutedClass }}">Explore our most popular tours — luxury stays, wildlife adventures, and flexible itineraries built to inspire.</p>
        </div>

        @if ($featuredTours->isEmpty())
            <p class="mt-10 text-center text-slate-500">No tours available yet. Add some in the admin panel.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mt-10">
                @foreach ($featuredTours as $tour)
                    @php
                        $displayPrice = $tour->discount_price && $tour->discount_price < $tour->price
                            ? $tour->discount_price
                            : $tour->price;
                        $hasDiscount = $tour->discount_price && $tour->discount_price < $tour->price;
                        $imageUrl = $tour->featured_image
                            ? (str_starts_with($tour->featured_image, 'http') ? $tour->featured_image : asset('storage/' . $tour->featured_image))
                            : asset('images/creation-africa/hero1.jpg');
                    @endphp
                    <article class="group overflow-hidden rounded-2xl bg-white shadow-xl transition hover:shadow-2xl hover:-translate-y-1" style="border: 2px solid color-mix(in srgb, var(--color-primary) 20%, transparent);">
                        <div class="h-full flex flex-col">
                            {{-- Image (Full Width) --}}
                            <div class="card-media h-48 overflow-hidden flex-shrink-0 relative">
                                <img src="{{ $imageUrl }}" alt="{{ $tour->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                @if ($tour->category)
                                    <span class="absolute top-3 left-3 rounded-full bg-black/40 backdrop-blur-sm px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white">
                                        {{ $tour->category->name }}
                                    </span>
                                @endif
                                @if ($hasDiscount)
                                    <span class="absolute top-3 right-3 rounded-full px-2.5 py-1 text-[10px] font-bold text-white" style="background-color: var(--color-accent);">
                                        SALE
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-base font-black text-slate-950 leading-snug line-clamp-2">{{ $tour->title }}</h3>

                                @if ($tour->duration)
                                    <p class="mt-1.5 flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                                        </svg>
                                        {{ $tour->duration }}
                                    </p>
                                @endif

                                <div class="mt-auto pt-4 flex items-center justify-between gap-2">
                                    <div>
                                        @if ($tour->price)
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-lg font-black text-[var(--color-primary)]">${{ number_format($displayPrice, 0) }}</span>
                                                @if ($hasDiscount)
                                                    <span class="text-xs text-slate-400 line-through">${{ number_format($tour->price, 0) }}</span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-slate-400">per person</p>
                                        @endif
                                    </div>
                                    <a href="{{ url("/{$locale}/tours/{$tour->slug}") }}"
                                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-4 py-2 text-xs font-bold text-white shadow transition group-hover:bg-[var(--color-accent)] hover:scale-105">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ url("/{$locale}/tours") }}"
                   class="inline-flex items-center gap-2 rounded-full border border-[var(--color-primary)] px-8 py-3 text-sm font-bold text-[var(--color-primary)] transition hover:bg-[var(--color-primary)] hover:text-white">
                    Browse all tours
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>
