@php
    $__bgType  = $sectionSettings->contact_bg_type  ?? 'color';
    $__bgColor = $sectionSettings->contact_bg_color ?? '#ffffff';
    $__bgImage = $sectionSettings->contact_bg_image ?? '';
    $__style = '';
    if ($__bgType === 'color') {
        $__style = 'background-color: ' . $__bgColor . ';';
    } elseif ($__bgType === 'image' && $__bgImage) {
        $__style = 'background-image: url(' . asset('storage/' . $__bgImage) . '); background-size: cover; background-position: center; background-repeat: no-repeat;';
    }
@endphp
<section class="px-6 py-12 lg:px-8" style="{{ $__style }}">
    <div class="mx-auto max-w-7xl">
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
