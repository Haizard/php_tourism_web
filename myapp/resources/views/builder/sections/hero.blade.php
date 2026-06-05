@php
    $overlayOpacity = $c['overlay_opacity'] ?? '0.65';
    $bgType = $c['bg_type'] ?? 'none';
@endphp

<section class="relative overflow-hidden min-h-[480px] flex items-center">
    @if($bgType === 'image' && !empty($c['bg_image']))
        <div class="absolute inset-0" style="background-image: url({{ asset('storage/' . $c['bg_image']) }}); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-black" style="opacity: {{ $overlayOpacity }};"></div>
    @endif

    <div class="relative mx-auto max-w-7xl px-6 py-24 lg:px-8 text-center w-full">
        @if(!empty($c['badge_text']))
            <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white/80 mb-6">
                {{ $c['badge_text'] }}
            </span>
        @endif

        @if(!empty($c['title']))
            <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">
                {{ $c['title'] }}
            </h1>
        @endif

        @if(!empty($c['subtitle']))
            <p class="mt-6 max-w-2xl mx-auto text-lg leading-8 text-white/85">
                {{ $c['subtitle'] }}
            </p>
        @endif

        @if(!empty($c['primary_btn_text']) || !empty($c['secondary_btn_text']))
            <div class="mt-10 flex flex-wrap gap-4 justify-center">
                @if(!empty($c['primary_btn_text']))
                    <a href="{{ $c['primary_btn_url'] ?? '#' }}"
                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-7 py-3 text-sm font-semibold text-white shadow-xl transition hover:-translate-y-0.5 hover:opacity-90">
                        {{ $c['primary_btn_text'] }}
                    </a>
                @endif
                @if(!empty($c['secondary_btn_text']))
                    <a href="{{ $c['secondary_btn_url'] ?? '#' }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                        {{ $c['secondary_btn_text'] }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
