@php
    $bg = $sectionBackgrounds['contact'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
@endphp

<section class="px-6 py-12 lg:px-8 relative" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">Get in Touch</h2>
            <p class="mt-4 text-slate-600">
                For travel planning, tours, or destination questions, reach us at
                <a href="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}" class="font-semibold text-[var(--color-primary)]">
                    {{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}
                </a>
                or call
                <a href="tel:{{ $generalSettings->contactPhone }}" class="font-semibold text-[var(--color-primary)]">
                    {{ $generalSettings->contactPhone }}
                </a>.
            </p>
            <p class="mt-4 text-slate-600">
                Our travel team is ready to support your next adventure. Booking and notification workflows are coming soon.
            </p>
        </x-glass-card>
    </div>
</section>
