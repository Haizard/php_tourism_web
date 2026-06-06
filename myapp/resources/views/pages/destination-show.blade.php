<x-layouts.app :title="$destination->seo_meta_title ?: $destination->name" :description="$destination->seo_meta_description ?: $destination->description">

    {{-- Hero --}}
    <section class="relative overflow-hidden">
        @if ($destination->featured_image)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($destination->featured_image) }}" alt="{{ $destination->name }}" class="h-full w-full object-cover" />
                <div class="absolute inset-0 bg-slate-950/60"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)]"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="max-w-2xl text-white">
                <a href="{{ url("/{$currentLocale}/destinations") }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/75 hover:text-white transition mb-6">
                    ← All Destinations
                </a>
                <h1 class="text-5xl font-black tracking-tight">{{ $destination->name }}</h1>
                @if ($destination->description)
                    <p class="mt-6 text-lg leading-8 text-white/85">{{ $destination->description }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Tours in this destination --}}
    @if ($tours->count())
        <section class="px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="page-section-header">
                    <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Available Tours</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-950">Tours in {{ $destination->name }}</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mt-10">
                    @foreach ($tours as $tour)
                        <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-900/5 transition hover:-translate-y-1">
                            @if ($tour->featured_image)
                                <img class="h-56 w-full object-cover" src="{{ Storage::url($tour->featured_image) }}" alt="{{ $tour->title }}" loading="lazy" />
                            @endif
                            <div class="p-6">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[var(--color-primary)]">
                                    {{ $tour->duration ?? '' }}
                                </p>
                                <h3 class="mt-2 text-xl font-black text-slate-950">{{ $tour->title }}</h3>
                                @if ($tour->excerpt)
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ Str::limit($tour->excerpt, 120) }}</p>
                                @endif
                                <div class="mt-4 flex items-center justify-between">
                                    @if ($tour->price)
                                        <span class="text-lg font-black text-[var(--color-primary)]">${{ number_format($tour->price, 0) }}</span>
                                    @endif
                                    <a href="{{ url("/{$currentLocale}/tours/{$tour->slug}") }}"
                                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white transition group-hover:bg-[var(--color-accent)]">
                                        View Tour
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-12 text-center shadow-lg">
                    <p class="text-slate-500">No tours available for this destination yet.</p>
                    <a href="{{ url("/{$currentLocale}/tours") }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                        Browse All Tours
                    </a>
                </div>
            </div>
        </section>
    @endif

</x-layouts.app>
