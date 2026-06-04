<x-layouts.app :title="'About Us | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$generalSettings->tagline">
    <x-page-header
        eyebrow="About"
        title="Built for travel brands and multilingual experiences"
        subtitle="A polished tourism website foundation with admin-managed branding, multilingual routing, and visual storytelling."
        image="{{ asset('images/creation-africa/elephant.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Talk to our team"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-glass-card class="space-y-8">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Our story</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Built for travel brands and multilingual experiences</h1>
                    <p class="mt-4 max-w-2xl text-slate-600">
                        {{ $generalSettings->siteName ?? 'Tourism Starter Kit' }} combines modern Laravel architecture with a polished public site,
                        admin-managed settings, and a flexible locale-aware experience for customers worldwide.
                    </p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4 rounded-3xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                        <h2 class="text-xl font-semibold text-slate-950">What we deliver</h2>
                        <ul class="space-y-3 text-slate-600">
                            <li>Multilingual public routing with locale awareness.</li>
                            <li>Admin-editable branding, theme, and SEO settings.</li>
                            <li>Responsive design with modern Tailwind visual polish.</li>
                        </ul>
                    </div>
                    <div class="space-y-4 rounded-3xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                        <h2 class="text-xl font-semibold text-slate-950">How it helps your agency</h2>
                        <p class="text-slate-600">
                            Customers can browse tours, destinations, and contact information in their own language,
                            while marketers keep the brand and metadata up to date through a central admin panel.
                        </p>
                        <p class="text-slate-600">
                            Every site color, logo, and hero overlay is now driven from theme settings for quick restyling.
                        </p>
                    </div>
                </div>
            </x-glass-card>
        </div>
    </section>
</x-layouts.app>
