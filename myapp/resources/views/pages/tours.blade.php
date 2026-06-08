<x-layouts.app :title="'Tour Packages | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Explore curated travel experiences powered by our admin-driven destination platform.'">
    <x-page-header
        eyebrow="Tour packages"
        title="Curated journeys for every traveler"
        subtitle="From family safaris to luxury retreats, every itinerary is built with thoughtful experiences and local expertise."
        image="{{ asset('images/creation-africa/tanzania-lodge-safaris.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Plan a safari"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-glass-card class="space-y-12">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Tour packages</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Curated journeys for every traveler</h1>
                    <p class="mt-4 max-w-2xl text-slate-600">
                        Discover flexible travel collections tailored for families, couples, and adventure seekers.
                        Each tour is designed to showcase destinations, itineraries, and unforgettable experiences.
                    </p>
                </div>

                @php
                    $tours = \App\Models\Tour::where('is_published', true)->get();
                @endphp

                @if($tours->count() > 0)
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($tours as $tour)
                            <a href="{{ route('tours.show', ['locale' => $currentLocale, 'slug' => $tour->slug]) }}" class="group rounded-3xl border border-slate-200 bg-white/80 overflow-hidden shadow-lg shadow-slate-200/30 transition-all hover:shadow-xl hover:shadow-slate-200/40">
                                @if($tour->featured_image)
                                    <div class="relative h-48 overflow-hidden bg-slate-100">
                                        <img src="{{ asset('storage/' . $tour->featured_image) }}" alt="{{ $tour->title }}" class="h-full w-full object-cover transition-transform group-hover:scale-105" loading="lazy">
                                    </div>
                                @else
                                    <div class="flex h-48 items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                        <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-slate-950 group-hover:text-[var(--color-accent)] transition-colors">{{ $tour->title }}</h2>
                                    @if($tour->excerpt)
                                        <p class="mt-2 text-slate-600 line-clamp-2">{{ Str::limit($tour->excerpt, 150) }}</p>
                                    @endif
                                    @if($tour->price)
                                        <div class="mt-4 flex items-center justify-between">
                                            <span class="text-2xl font-bold text-[var(--color-accent)]">${{ number_format($tour->price, 2) }}</span>
                                            <span class="inline-block rounded-lg bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white transition-all group-hover:opacity-90">
                                                View →
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-12 text-center">
                        <p class="text-slate-600">No tour packages available yet. Check back soon!</p>
                    </div>
                @endif
            </x-glass-card>
        </div>
    </section>
</x-layouts.app>
