<x-layouts.app :title="'FAQ | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Frequently asked questions about our multilingual travel platform and booking support.'">
    <x-page-header
        eyebrow="FAQ"
        title="Questions travelers ask most"
        subtitle="Get fast answers about language support, booking details, and how our platform powers your travel experiences."
        image="{{ asset('images/creation-africa/cheetah.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Ask a question"
    />
    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="space-y-8">
                <div class="rounded-4xl border border-slate-200 bg-white/80 p-10 shadow-lg shadow-slate-200/30">
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">FAQs</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Need help? Find quick answers here.</h1>
                    <p class="mt-4 max-w-2xl text-slate-600">Our platform is built to make travel content, locale switching, and admin-driven branding easy for your team.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ([
                        ['question' => 'Can I change the site language from the public pages?', 'answer' => 'Yes. Use the locale selector in the header to view the site in any enabled language instantly.'],
                        ['question' => 'Where do I update branding and colors?', 'answer' => 'Branding, logo, favicon, theme colors, and SEO settings are managed through the admin panel under Settings.'],
                        ['question' => 'How do I contact the travel team?', 'answer' => 'Use the contact page email or phone details displayed on the site. Those values are automatically pulled from site settings.'],
                        ['question' => 'Is the site ready for RTL languages?', 'answer' => 'Yes. The locale middleware and layout support right-to-left languages such as Arabic when enabled in settings.'],
                    ] as $faq)
                        <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                            <h2 class="text-xl font-semibold text-slate-950">{{ $faq['question'] }}</h2>
                            <p class="mt-3 text-slate-600">{{ $faq['answer'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
