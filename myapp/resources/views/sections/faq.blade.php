@php
    $bg = $sectionBackgrounds['faq'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $sectionTextClass = $bg ? $bg->text_class : 'text-slate-950';
@endphp

<section class="px-6 py-16 lg:px-8 relative {{ $sectionTextClass }}" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">FAQ Placeholder</h2>
            <p class="mt-4 text-slate-600">Frequently asked questions will become editable content later.</p>
        </x-glass-card>
    </div>
</section>
