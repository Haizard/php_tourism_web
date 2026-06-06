@php
    $bg = $sectionBackgrounds['about'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
    $mutedClass = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
@endphp

<section class="px-6 py-16 lg:px-8 relative" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header text-center">
            <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Our Responsibilities</span>
            <h2 class="mt-4 text-4xl font-black {{ $headingClass }}">The commitments that make every trip easier</h2>
            <p class="mt-3 mx-auto max-w-2xl {{ $mutedClass }}">We deliver travel planning that is dependable, flexible, and tailored to your needs at every step of the journey.</p>
        </div>

        <div class="grid gap-6 mt-10 lg:grid-cols-4">
            <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 text-center shadow-lg shadow-slate-900/5">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">✓</div>
                <h3 class="mt-6 text-xl font-black text-slate-950">Always Available</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">For your help</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 text-center shadow-lg shadow-slate-900/5">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[var(--color-accent)]/10 text-[var(--color-accent)]">✦</div>
                <h3 class="mt-6 text-xl font-black text-slate-950">Cancel Free of Charge</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">With FLEX TARIF</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 text-center shadow-lg shadow-slate-900/5">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-violet-100 text-[var(--color-primary)]">✈</div>
                <h3 class="mt-6 text-xl font-black text-slate-950">Train to Flight Ticket</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Seamless transfer support</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 text-center shadow-lg shadow-slate-900/5">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-pink-100 text-[var(--color-accent)]">✧</div>
                <h3 class="mt-6 text-xl font-black text-slate-950">Flyloco Angebote</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">A modern travel guarantee</p>
            </div>
        </div>
    </div>
</section>
