@php
    $__bgType  = $sectionSettings->faq_bg_type  ?? 'color';
    $__bgColor = $sectionSettings->faq_bg_color ?? '#ffffff';
    $__bgImage = $sectionSettings->faq_bg_image ?? '';
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
            <h2 class="text-3xl font-black text-slate-950">FAQ Placeholder</h2>
            <p class="mt-4 text-slate-600">Frequently asked questions will become editable content later.</p>
        </x-glass-card>
    </div>
</section>
