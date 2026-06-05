@php
    $bgType = $c['bg_type'] ?? 'color';
    $hasImage = $bgType === 'image' && !empty($c['bg_image']);
    $textColor = ($bgType !== 'none') ? 'text-white' : 'text-slate-900';
@endphp

<section class="relative py-20 overflow-hidden">
    @if($hasImage)
        <div class="absolute inset-0" style="background-image: url({{ asset('storage/' . $c['bg_image']) }}); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-slate-900/65"></div>
    @endif

    <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
        @if(!empty($c['title']))
            <h2 class="text-3xl font-bold tracking-tight {{ $textColor }} sm:text-4xl">
                {{ $c['title'] }}
            </h2>
        @endif
        @if(!empty($c['subtitle']))
            <p class="mt-4 text-lg {{ $textColor }}/80">{{ $c['subtitle'] }}</p>
        @endif
        @if(!empty($c['btn_text']) || !empty($c['secondary_btn_text']))
            <div class="mt-10 flex flex-wrap gap-4 justify-center">
                @if(!empty($c['btn_text']))
                    <a href="{{ $c['btn_url'] ?? '#' }}"
                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-8 py-3.5 text-sm font-semibold text-white shadow-xl transition hover:-translate-y-0.5 hover:opacity-90">
                        {{ $c['btn_text'] }}
                    </a>
                @endif
                @if(!empty($c['secondary_btn_text']))
                    <a href="{{ $c['secondary_btn_url'] ?? '#' }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-8 py-3.5 text-sm font-semibold {{ $textColor }} transition hover:bg-white/20">
                        {{ $c['secondary_btn_text'] }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
