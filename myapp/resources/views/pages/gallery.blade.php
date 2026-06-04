<x-layouts.app :title="'Gallery | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'A curated collection of destination imagery and travel moments for modern explorers.'">
    <x-page-header
        eyebrow="Gallery"
        title="A visual journey through Africa"
        subtitle="Browse beautiful imagery of safari camps, wildlife, and cultural moments to inspire your next adventure."
        image="{{ asset('images/creation-africa/lion.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Start planning"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    'hero1.jpg' => 'Safari sunrise over open plains',
                    'safari.jpg' => 'Wildlife viewing at first light',
                    'elephant.jpg' => 'A herd moving through the savanna',
                    'ngorongor-crater-banner.jpg' => 'Crater rim panoramas',
                    'lion.jpg' => 'Big cat portraits in the wild',
                    'cheetah.jpg' => 'A cheetah on the move',
                ] as $file => $caption)
                    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-lg shadow-slate-900/5">
                        <img src="{{ asset('images/creation-africa/'.$file) }}" alt="{{ $caption }}" class="h-80 w-full object-cover transition duration-700 hover:scale-105" loading="lazy" />
                        <div class="p-6">
                            <p class="text-sm font-semibold text-slate-800">{{ $caption }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
