@php
    $bg = $sectionBackgrounds['statistics'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
@endphp

<section class="px-6 py-12 lg:px-8 relative" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
        <x-glass-card class="space-y-4">
            <p class="text-4xl font-black text-[var(--color-primary)]">6+</p>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Supported locales</p>
            <p class="text-slate-600">Give customers the confidence to browse in their preferred language with locale-aware routing and RTL support.</p>
        </x-glass-card>
        <x-glass-card class="space-y-4">
            <p class="text-4xl font-black text-[var(--color-primary)]">100+</p>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Tour ideas</p>
            <p class="text-slate-600">Present curated safari and destination experiences with rich visuals and admin-managed content.</p>
        </x-glass-card>
        <x-glass-card class="space-y-4">
            <p class="text-4xl font-black text-[var(--color-primary)]">24/7</p>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Booking support</p>
            <p class="text-slate-600">Keep traveler support information front and center across contact pages and footer sections.</p>
        </x-glass-card>
    </div>
</section>
