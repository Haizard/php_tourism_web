@php
    $bg = $sectionBackgrounds['gallery'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
    $mutedClass = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
@endphp

<section class="px-6 py-12 lg:px-8 relative" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header">
            <span class="badge-pill bg-[var(--color-accent)]/10 text-[var(--color-accent)]">Gallery</span>
            <h2 class="mt-4 text-3xl font-black {{ $headingClass }}">Immerse in travel moments</h2>
            <p class="mt-3 max-w-2xl {{ $mutedClass }}">Browse a selection of destination imagery that brings the safari experience to life.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                'hero1.jpg' => 'Safari sunrise over open plains',
                'safari.jpg' => 'Wildlife viewing at first light',
                'elephant.jpg' => 'A family of elephants near the river',
                'ngorongor-crater-banner.jpg' => 'The dramatic Ngorongoro Crater landscape',
                'lion.jpg' => 'Big cat portrait in the wild',
                'cheetah.jpg' => 'A cheetah on the move',
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
