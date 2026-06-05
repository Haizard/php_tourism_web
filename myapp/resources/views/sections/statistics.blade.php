@php
    $__bgType  = $sectionSettings->statistics_bg_type  ?? 'color';
    $__bgColor = $sectionSettings->statistics_bg_color ?? '#ffffff';
    $__bgImage = $sectionSettings->statistics_bg_image ?? '';
    $__style = '';
    if ($__bgType === 'color') {
        $__style = 'background-color: ' . $__bgColor . ';';
    } elseif ($__bgType === 'image' && $__bgImage) {
        $__style = 'background-image: url(' . asset('storage/' . $__bgImage) . '); background-size: cover; background-position: center; background-repeat: no-repeat;';
    }
@endphp
<section class="px-6 py-12 lg:px-8" style="{{ $__style }}">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
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
