@php
    $__bgType  = $sectionSettings->about_bg_type  ?? 'color';
    $__bgColor = $sectionSettings->about_bg_color ?? '#f1f5f9';
    $__bgImage = $sectionSettings->about_bg_image ?? '';
    $__style = '';
    if ($__bgType === 'color') {
        $__style = 'background-color: ' . $__bgColor . ';';
    } elseif ($__bgType === 'image' && $__bgImage) {
        $__style = 'background-image: url(' . asset('storage/' . $__bgImage) . '); background-size: cover; background-position: center; background-repeat: no-repeat;';
    }
@endphp
<section class="px-6 py-16 lg:px-8" style="{{ $__style }}">
    <div class="mx-auto max-w-7xl">
        <div class="page-section-header text-center">
            <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Our Responsibilities</span>
            <h2 class="mt-4 text-4xl font-black text-slate-950">The commitments that make every trip easier</h2>
            <p class="mt-3 mx-auto max-w-2xl text-slate-600">We deliver travel planning that is dependable, flexible, and tailored to your needs at every step of the journey.</p>
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
