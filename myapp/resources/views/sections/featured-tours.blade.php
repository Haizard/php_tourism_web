@php
    $bg = $sectionBackgrounds['featured_tours'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $textClass = ($bg && $bg->text_color === 'light') ? 'text-white' : '';
    $mutedClass = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
@endphp

<section class="px-6 py-16 lg:px-8 relative" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header text-center">
            <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Hot deals</span>
            <h2 class="mt-4 text-4xl font-black {{ $headingClass }}">Fire up your savings with our hot deals</h2>
            <p class="mt-3 mx-auto max-w-2xl {{ $mutedClass }}">Hand-picked offers for iconic destinations, luxury stays, and flexible itineraries built to save you time and money.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-4 mt-10">
            <article class="group overflow-hidden rounded-[2rem] bg-gradient-to-br from-[var(--color-primary)]/10 via-white/40 to-[var(--color-accent)]/10 p-1 shadow-2xl shadow-slate-900/10 transition hover:-translate-y-1">
                <div class="rounded-[2rem] bg-white/90 p-6 h-full">
                    <div class="card-media h-52 rounded-[1.75rem] overflow-hidden">
                        <img src="{{ asset('images/creation-africa/hero1.jpg') }}" alt="White House" loading="lazy" />
                    </div>
                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-primary)]">White House</p>
                        <h3 class="mt-3 text-xl font-black text-slate-950">7 Days</h3>
                        <p class="mt-4 text-sm leading-6 text-slate-600">Luxury · Breakfast</p>
                        <a href="#" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition group-hover:bg-[var(--color-accent)]">Discover Now</a>
                    </div>
                </div>
            </article>

            <article class="group overflow-hidden rounded-[2rem] bg-gradient-to-br from-[var(--color-primary)]/10 via-white/40 to-[var(--color-accent)]/10 p-1 shadow-2xl shadow-slate-900/10 transition hover:-translate-y-1">
                <div class="rounded-[2rem] bg-white/90 p-6 h-full">
                    <div class="card-media h-52 rounded-[1.75rem] overflow-hidden">
                        <img src="{{ asset('images/creation-africa/ngorongor-crater-banner.jpg') }}" alt="Egypt Pyramids" loading="lazy" />
                    </div>
                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-primary)]">Egypt Pyramids</p>
                        <h3 class="mt-3 text-xl font-black text-slate-950">7 Days</h3>
                        <p class="mt-4 text-sm leading-6 text-slate-600">Luxury · Breakfast</p>
                        <a href="#" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition group-hover:bg-[var(--color-accent)]">Discover Now</a>
                    </div>
                </div>
            </article>

            <article class="group overflow-hidden rounded-[2rem] bg-gradient-to-br from-[var(--color-primary)]/10 via-white/40 to-[var(--color-accent)]/10 p-1 shadow-2xl shadow-slate-900/10 transition hover:-translate-y-1">
                <div class="rounded-[2rem] bg-white/90 p-6 h-full">
                    <div class="card-media h-52 rounded-[1.75rem] overflow-hidden">
                        <img src="{{ asset('images/creation-africa/cheetah.jpg') }}" alt="Taj Mahal" loading="lazy" />
                    </div>
                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-primary)]">Taj Mahal</p>
                        <h3 class="mt-3 text-xl font-black text-slate-950">7 Days</h3>
                        <p class="mt-4 text-sm leading-6 text-slate-600">Luxury · Breakfast</p>
                        <a href="#" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition group-hover:bg-[var(--color-accent)]">Discover Now</a>
                    </div>
                </div>
            </article>

            <article class="group overflow-hidden rounded-[2rem] bg-gradient-to-br from-[var(--color-primary)]/10 via-white/40 to-[var(--color-accent)]/10 p-1 shadow-2xl shadow-slate-900/10 transition hover:-translate-y-1">
                <div class="rounded-[2rem] bg-white/90 p-6 h-full">
                    <div class="card-media h-52 rounded-[1.75rem] overflow-hidden">
                        <img src="{{ asset('images/creation-africa/lion.jpg') }}" alt="Faisal Mosque" loading="lazy" />
                    </div>
                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-primary)]">Faisal Mosque</p>
                        <h3 class="mt-3 text-xl font-black text-slate-950">7 Days</h3>
                        <p class="mt-4 text-sm leading-6 text-slate-600">Luxury · Breakfast</p>
                        <a href="#" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-5 py-3 text-sm font-semibold text-white transition group-hover:bg-[var(--color-accent)]">Discover Now</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
