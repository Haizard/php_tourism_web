<x-layouts.app :title="'Privacy Policy | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Our privacy policy explains how we handle personal information across the multilingual travel platform.'">
    <x-page-header
        eyebrow="Privacy"
        title="Protecting your information"
        subtitle="We respect your privacy and explain how contact details and travel inquiries are handled on our platform."
        image="{{ asset('images/creation-africa/ngorongor-crater-banner.jpg') }}"
        ctaUrl="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}"
        ctaText="Contact privacy team"
    />
    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-6xl space-y-10">
            <div class="rounded-4xl border border-slate-200 bg-white/80 p-10 shadow-lg shadow-slate-200/30">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Privacy Policy</p>
                <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Protecting your information</h1>
                <p class="mt-4 text-slate-600">At {{ $generalSettings->siteName ?? 'our site' }}, we take privacy seriously. This policy explains what information we collect, how we use it, and how we protect it as you explore travel content and contact our team.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                    <h2 class="text-2xl font-semibold text-slate-950">Information we collect</h2>
                    <p class="mt-4 text-slate-600">We collect contact details when you choose to reach out, analytics data to improve site performance, and any information you provide during booking inquiries.</p>
                </div>
                <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                    <h2 class="text-2xl font-semibold text-slate-950">How we use data</h2>
                    <p class="mt-4 text-slate-600">Data is used to support your inquiry, improve our public site experience, and maintain the quality of multilingual content. We never sell personal information.</p>
                </div>
            </div>

            <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                <h2 class="text-2xl font-semibold text-slate-950">Security</h2>
                <p class="mt-4 text-slate-600">We follow standard security practices to protect stored settings and contact information. Access to admin settings and account data is restricted through authentication and permissions.</p>
            </div>

            <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                <h2 class="text-2xl font-semibold text-slate-950">Contact privacy questions</h2>
                <p class="mt-4 text-slate-600">If you have questions about privacy or data use, contact us at <a href="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}" class="font-semibold text-[var(--color-primary)]">{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}</a>.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
