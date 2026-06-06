@php
    $bg = $sectionBackgrounds['hero'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $textClass = ($bg && $bg->text_color === 'light') ? 'text-white' : '';
@endphp

<section class="page-hero {{ $hasBg ? '' : 'page-hero--hero1' }} relative overflow-hidden" style="{{ $bgStyle }}">
    @if (!$hasBg)
        <div class="page-hero__image absolute inset-0"></div>
    @endif
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @elseif (!$hasBg)
        <div class="page-hero__overlay absolute inset-0 bg-slate-950/75"></div>
    @endif

    <div class="relative mx-auto max-w-7xl px-6 py-20 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1.12fr_0.88fr] lg:items-center">
            <div class="max-w-2xl {{ $hasBg && $bg->text_color === 'light' ? 'text-white' : 'text-white' }}">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-pink-200 shadow-[0_20px_80px_rgba(236,72,153,0.08)]">
                    Ticket Plan
                </span>
                <h1 class="mt-8 text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">Explore Beautiful World With Us</h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-100/90">"Let us take the hassle out of travel planning, so you can focus on the adventure ahead."</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ url("/{$currentLocale}/tours") }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[var(--color-primary)] via-[var(--color-accent)] to-[var(--color-primary)] px-7 py-3 text-sm font-semibold text-white shadow-2xl shadow-[rgba(140,92,246,0.28)] transition hover:-translate-y-0.5">
                        Discover Now
                    </a>
                    <a href="{{ url("/{$currentLocale}/contact") }}" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
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
