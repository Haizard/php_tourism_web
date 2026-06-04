@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
    'image' => null,
    'ctaUrl' => null,
    'ctaText' => null,
])

<section class="page-hero relative overflow-hidden text-white">
    @if ($image)
        <div class="page-hero__image absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $image }}');"></div>
    @endif
    <div class="page-hero__overlay absolute inset-0 bg-gradient-to-br from-slate-950/80 via-slate-950/40 to-transparent"></div>
    <div class="relative mx-auto flex min-h-[420px] max-w-7xl items-center px-6 py-20 lg:px-8">
        <div class="max-w-3xl">
            @if ($eyebrow)
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[var(--color-accent)]">{{ $eyebrow }}</p>
            @endif
            <h1 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-100 sm:text-lg">{{ $subtitle }}</p>
            @endif
            @if ($ctaUrl && $ctaText)
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ $ctaUrl }}" class="inline-flex items-center rounded-full bg-[var(--color-primary)] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/20 transition hover:-translate-y-0.5">{{ $ctaText }}</a>
                </div>
            @endif
        </div>
    </div>
</section>
