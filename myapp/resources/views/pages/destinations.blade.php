<x-layouts.app :title="'Destinations | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Browse destination highlights and travel inspiration powered by a flexible multilingual platform.'">
    <x-page-header
        eyebrow="Destinations"
        title="Explore inspiring destinations worldwide"
        subtitle="Every destination page is designed to showcase local highlights, cultural context, and adventure options."
        image="{{ asset('images/creation-africa/ngorongor-crater-banner.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Find your destination"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-glass-card class="space-y-8">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Destinations</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Explore destinations worldwide</h1>
                    <p class="mt-4 max-w-2xl text-slate-600">
                        Every destination page is designed to give travelers the right overview, local context, and booking next steps.
                        Use the locale switcher to preview the same destinations in multiple languages.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-200/30">
                        <h2 class="text-xl font-semibold text-slate-950">Coastal escapes</h2>
                        <p class="mt-3 text-slate-600">Sun-soaked beach itineraries with local highlights and guided experiences.</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-200/30">
                        <h2 class="text-xl font-semibold text-slate-950">Mountain retreats</h2>
                        <p class="mt-3 text-slate-600">Nature-driven journeys and immersive adventure travel.</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-200/30">
                        <h2 class="text-xl font-semibold text-slate-950">City escapes</h2>
                        <p class="mt-3 text-slate-600">Urban highlights, cultural tours, and vibrant local food scenes.</p>
                    </div>
                </div>
            </x-glass-card>
        </div>
    </section>
</x-layouts.app>
