@php
    $bg = $sectionBackgrounds['newsletter'] ?? null;
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
        <x-glass-card class="grid gap-8 lg:grid-cols-[1.5fr_1fr] items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[var(--color-accent)]">Stay updated</p>
                <h2 class="mt-4 text-3xl font-black text-slate-950">Get new trip ideas and travel tips.</h2>
                <p class="mt-4 text-slate-600">Subscribe to the newsletter for destination highlights, seasonal offers, and itinerary inspiration.</p>
            </div>
            <form action="#" class="space-y-4 rounded-3xl border border-slate-200 bg-slate-950 p-6 text-white shadow-lg shadow-slate-950/10">
                <label class="block text-sm font-semibold uppercase tracking-[0.2em] text-slate-300" for="newsletter-email">Email address</label>
                <input id="newsletter-email" type="email" class="w-full rounded-3xl border border-slate-700 bg-slate-900 px-5 py-3 text-sm text-white outline-none ring-2 ring-transparent transition focus:ring-[var(--color-primary)]" placeholder="you@example.com" />
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-primary)] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5">Subscribe</button>
            </form>
        </x-glass-card>
    </div>
</section>
