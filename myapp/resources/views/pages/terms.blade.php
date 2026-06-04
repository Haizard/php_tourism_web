<x-layouts.app :title="'Terms and Conditions | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Terms and conditions for using our multilingual travel website and admin-managed destination content.'">
    <x-page-header
        eyebrow="Terms"
        title="Using our platform with confidence"
        subtitle="These terms cover how travelers use our site, contact the team, and review travel information online."
        image="{{ asset('images/creation-africa/hero1.jpg') }}"
        ctaUrl="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}"
        ctaText="Contact support"
    />
    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-6xl space-y-10">
            <div class="rounded-4xl border border-slate-200 bg-white/80 p-10 shadow-lg shadow-slate-200/30">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Terms & Conditions</p>
                <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Using our platform with confidence</h1>
                <p class="mt-4 text-slate-600">By using {{ $generalSettings->siteName ?? 'our site' }}, you agree to the policies below. These terms outline content usage, site interactions, and your responsibilities when exploring travel information and contacting our team.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                    <h2 class="text-2xl font-semibold text-slate-950">Use of content</h2>
                    <p class="mt-4 text-slate-600">All content on this site is provided for informational purposes. Images, destination details, and suggested itineraries are illustrative and subject to change.</p>
                </div>
                <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                    <h2 class="text-2xl font-semibold text-slate-950">Booking inquiries</h2>
                    <p class="mt-4 text-slate-600">Contact details and inquiries are collected to support travel requests. Any actual booking agreements will be confirmed separately with travel providers.</p>
                </div>
            </div>

            <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                <h2 class="text-2xl font-semibold text-slate-950">Limitation of liability</h2>
                <p class="mt-4 text-slate-600">We are not responsible for third-party services, travel provider availability, or any loss arising from using the information on this website.</p>
            </div>

            <div class="rounded-4xl border border-slate-200 bg-white/80 p-8 shadow-lg shadow-slate-200/30">
                <h2 class="text-2xl font-semibold text-slate-950">Governing law</h2>
                <p class="mt-4 text-slate-600">These terms are governed by the applicable laws of the business entity operating the site, and any disputes will be handled in accordance with those laws.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
