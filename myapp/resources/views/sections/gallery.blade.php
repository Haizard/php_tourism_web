@php
    $__bgType  = $sectionSettings->gallery_bg_type  ?? 'color';
    $__bgColor = $sectionSettings->gallery_bg_color ?? '#ffffff';
    $__bgImage = $sectionSettings->gallery_bg_image ?? '';
    $__style = '';
    if ($__bgType === 'color') {
        $__style = 'background-color: ' . $__bgColor . ';';
    } elseif ($__bgType === 'image' && $__bgImage) {
        $__style = 'background-image: url(' . asset('storage/' . $__bgImage) . '); background-size: cover; background-position: center; background-repeat: no-repeat;';
    }
@endphp
<section class="px-6 py-12 lg:px-8" style="{{ $__style }}">
    <div class="mx-auto max-w-7xl">
        <div class="page-section-header">
            <span class="badge-pill bg-[var(--color-accent)]/10 text-[var(--color-accent)]">Gallery</span>
            <h2 class="mt-4 text-3xl font-black text-slate-950">Immerse in travel moments</h2>
            <p class="mt-3 max-w-2xl text-slate-600">Browse a selection of destination imagery that brings the safari experience to life.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                'hero1.jpg'                      => 'Safari sunrise over open plains',
                'safari.jpg'                     => 'Wildlife viewing at first light',
                'elephant.jpg'                   => 'A family of elephants near the river',
                'ngorongor-crater-banner.jpg'    => 'The dramatic Ngorongoro Crater landscape',
                'lion.jpg'                       => 'Big cat portrait in the wild',
                'cheetah.jpg'                    => 'A cheetah on the move',
            ] as $file => $caption)
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-lg shadow-slate-900/5">
                    <img src="{{ asset('images/creation-africa/'.$file) }}" alt="{{ $caption }}" class="h-80 w-full object-cover transition duration-700 hover:scale-105" loading="lazy" />
                    <div class="p-6">
                        <p class="text-sm font-semibold text-slate-800">{{ $caption }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
