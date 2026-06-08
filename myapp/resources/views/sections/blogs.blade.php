@php
    $bg = $sectionBackgrounds['blogs'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $sectionTextClass = $bg ? $bg->text_class : 'text-slate-950';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
    $mutedClass = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
@endphp

<section class="px-6 py-12 lg:px-8 relative {{ $sectionTextClass }}" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header">
            <span class="badge-pill bg-[var(--color-accent)]/10 text-[var(--color-accent)]">Journal</span>
            <h2 class="mt-4 text-3xl font-black {{ $headingClass }}">Stories from the road</h2>
            <p class="mt-3 max-w-2xl {{ $mutedClass }}">Travel inspiration and local insights for people planning their next adventure.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-lg shadow-slate-900/5">
                <img class="h-56 w-full object-cover" src="{{ asset('images/creation-africa/ngorongor-crater-banner.jpg') }}" alt="Crater travel story" loading="lazy" />
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-accent)]">Travel insight</p>
                    <h3 class="mt-3 text-2xl font-black text-slate-950">Inside Ngorongoro</h3>
                    <p class="mt-4 text-sm leading-6 text-slate-600">A deeper look at what makes this crater one of East Africa’s most captivating safari destinations.</p>
                </div>
            </article>
            <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-lg shadow-slate-900/5">
                <img class="h-56 w-full object-cover" src="{{ asset('images/creation-africa/safari.jpg') }}" alt="Safari travel story" loading="lazy" />
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-accent)]">Guided itinerary</p>
                    <h3 class="mt-3 text-2xl font-black text-slate-950">Planning a safari</h3>
                    <p class="mt-4 text-sm leading-6 text-slate-600">How to choose the right game drive route, lodge style, and season for your group.</p>
                </div>
            </article>
            <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-lg shadow-slate-900/5">
                <img class="h-56 w-full object-cover" src="{{ asset('images/creation-africa/elephant.jpg') }}" alt="Elephant travel story" loading="lazy" />
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-accent)]">Local culture</p>
                    <h3 class="mt-3 text-2xl font-black text-slate-950">Wildlife & culture</h3>
                    <p class="mt-4 text-sm leading-6 text-slate-600">Blending wildlife safaris with cultural experiences for a more meaningful trip.</p>
                </div>
            </article>
        </div>
    </div>
</section>
