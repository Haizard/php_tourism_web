<x-layouts.app :title="'Contact | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$generalSettings->tagline">
    <x-page-header
        eyebrow="Contact"
        title="Start your travel planning today"
        subtitle="Reach out for itinerary support, booking guidance, and multilingual destination advice."
        image="{{ asset('images/creation-africa/safari.jpg') }}"
        ctaText="Send us a message"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-12 lg:grid-cols-2">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <x-glass-card class="space-y-8">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[var(--color-accent)]">Contact information</p>
                            <h2 class="mt-4 text-3xl font-bold text-slate-950">Get in Touch</h2>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-slate-600 uppercase tracking-widest">Email</p>
                                <a href="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}" class="block text-2xl font-bold text-[var(--color-accent)] hover:opacity-70 transition">
                                    {{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}
                                </a>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-slate-600 uppercase tracking-widest">Phone</p>
                                <a href="tel:{{ $generalSettings->contactPhone }}" class="block text-2xl font-bold text-slate-950 hover:text-[var(--color-accent)] transition">
                                    {{ $generalSettings->contactPhone }}
                                </a>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-slate-600 uppercase tracking-widest">Address</p>
                                <p class="text-lg text-slate-700">{{ $generalSettings->address }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-slate-600 uppercase tracking-widest">Hours</p>
                                <p class="text-lg text-slate-700">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="text-lg text-slate-700">Saturday - Sunday: 10:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </x-glass-card>
                </div>

                <!-- Contact Form -->
                <div>
                    <x-glass-card class="space-y-6">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[var(--color-accent)]">Message us</p>
                            <h2 class="mt-4 text-3xl font-bold text-slate-950">Send a Message</h2>
                            <p class="mt-2 text-slate-600">We'll get back to you as soon as possible.</p>
                        </div>

                        @if ($errors->any())
                            <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                                <p class="text-red-700 font-semibold">Please fix the errors below:</p>
                                <ul class="mt-2 space-y-1 text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="rounded-lg bg-green-50 p-4 border border-green-200">
                                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('contact.store', ['locale' => $currentLocale]) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-950">Full Name *</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" 
                                    placeholder="Your name" 
                                    required
                                >
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-950">Email *</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" 
                                    placeholder="your@email.com" 
                                    required
                                >
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-950">Phone</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" 
                                    placeholder="Your phone number"
                                >
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-slate-950">Subject *</label>
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    value="{{ old('subject') }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" 
                                    placeholder="How can we help?" 
                                    required
                                >
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-950">Message *</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" 
                                    placeholder="Your message..." 
                                    required
                                >{{ old('message') }}</textarea>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-lg hover:bg-green-700 hover:shadow-xl transition-all active:scale-95"
                            >
                                Send Message
                            </button>
                        </form>
                    </x-glass-card>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
