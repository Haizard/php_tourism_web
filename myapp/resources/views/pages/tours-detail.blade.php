<x-layouts.app :title="$tour->title . ' | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$tour->excerpt ?? 'Explore this amazing tour package.'">
    <x-page-header
        :title="$tour->title"
        :subtitle="$tour->excerpt"
        :image="$tour->featured_image ? asset('storage/' . $tour->featured_image) : asset('images/creation-africa/tanzania-lodge-safaris.jpg')"
        eyebrow="Tour Details"
        ctaText="Book This Tour"
    />

    {{-- Highlights Bar --}}
    @if($tour->highlights && count($tour->highlights) > 0)
    <div class="bg-white border-b border-slate-100 shadow-sm">
        <div class="mx-auto max-w-6xl px-6 py-4 lg:px-8">
            <div class="flex flex-wrap items-center gap-3">
                @foreach($tour->highlights as $highlight)
                    <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-[var(--color-accent)] hover:bg-[var(--color-accent)]/5 hover:text-[var(--color-accent)]">
                        @if(!empty($highlight['icon']))
                            <span class="text-base leading-none">{{ $highlight['icon'] }}</span>
                        @endif
                        <span>{{ $highlight['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-12 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <x-glass-card class="space-y-12">
                        @if($tour->itinerary)
                            <div>
                                <h2 class="text-2xl font-bold text-slate-950 mb-4">Itinerary</h2>
                                <div class="space-y-4">
                                    @foreach($tour->itinerary as $day => $activities)
                                        <div class="rounded-lg border border-slate-200 p-4 bg-white/50">
                                            <h3 class="font-semibold text-slate-950">{{ $day }}</h3>
                                            <p class="text-slate-600 mt-2">{{ $activities }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($tour->included_services)
                            <div>
                                <h2 class="text-2xl font-bold text-slate-950 mb-4">Included Services</h2>
                                <ul class="space-y-2">
                                    @foreach($tour->included_services as $service => $details)
                                        <li class="flex items-start gap-3">
                                            <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-slate-950">{{ $service }}</p>
                                                <p class="text-slate-600 text-sm">{{ $details }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($tour->excluded_services)
                            <div>
                                <h2 class="text-2xl font-bold text-slate-950 mb-4">Excluded Services</h2>
                                <ul class="space-y-2">
                                    @foreach($tour->excluded_services as $service => $details)
                                        <li class="flex items-start gap-3">
                                            <svg class="h-5 w-5 text-slate-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-slate-950">{{ $service }}</p>
                                                <p class="text-slate-600 text-sm">{{ $details }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <h2 class="text-2xl font-bold text-slate-950 mb-4">Tour Overview</h2>
                            <div class="prose prose-slate max-w-none">
                                {!! $tour->content !!}
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-8">
                            <a href="{{ url('/' . $currentLocale . '/tours') }}" class="inline-flex items-center gap-2 text-[var(--color-accent)] font-semibold hover:opacity-70 transition-opacity">
                                ← Back to Tours
                            </a>
                        </div>
                    </x-glass-card>
                </div>

                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Tour Info Card -->
                        <div class="sticky top-8">
                            <x-glass-card class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-950">Tour Details</h3>
                            </div>

                            @if($tour->price)
                                <div class="rounded-xl bg-[var(--color-accent)]/10 p-4">
                                    <p class="text-sm text-slate-600">Price per person</p>
                                    <p class="mt-2 text-3xl font-bold text-[var(--color-accent)]">
                                        ${{ number_format($tour->discount_price ?? $tour->price, 2) }}
                                    </p>
                                    @if($tour->discount_price)
                                        <p class="mt-2 text-sm line-through text-slate-500">
                                            Regular: ${{ number_format($tour->price, 2) }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-4">
                                @if($tour->duration)
                                    <div>
                                        <p class="text-sm font-semibold text-slate-950">Duration</p>
                                        <p class="mt-1 text-slate-600">{{ $tour->duration }}</p>
                                    </div>
                                @endif

                                @if($tour->destination)
                                    <div>
                                        <p class="text-sm font-semibold text-slate-950">Destination</p>
                                        <p class="mt-1 text-slate-600">{{ $tour->destination->name }}</p>
                                    </div>
                                @endif

                                @if($tour->category)
                                    <div>
                                        <p class="text-sm font-semibold text-slate-950">Category</p>
                                        <p class="mt-1 text-slate-600">{{ $tour->category->name }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Status</p>
                                    <p class="mt-1">
                                        @if($tour->is_published)
                                            <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Published</span>
                                        @else
                                            <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">Draft</span>
                                        @endif
                                    </p>
                                </div>

                                @if($tour->published_at)
                                    <div>
                                        <p class="text-sm font-semibold text-slate-950">Published</p>
                                        <p class="mt-1 text-slate-600">{{ $tour->published_at->format('F d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </x-glass-card>
                        </div>

                        <!-- Booking Form Card -->
                        <div class="sticky top-8">
                            <x-glass-card class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-950">Book This Tour</h3>
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
                                    <p class="text-green-700">{{ session('success') }}</p>
                                </div>
                            @endif

                            <form action="{{ route('booking.store', ['locale' => $currentLocale, 'tour' => $tour->id]) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="guest_name" class="block text-sm font-semibold text-slate-950">Full Name *</label>
                                    <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" required>
                                </div>

                                <div>
                                    <label for="guest_email" class="block text-sm font-semibold text-slate-950">Email *</label>
                                    <input type="email" id="guest_email" name="guest_email" value="{{ old('guest_email') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" required>
                                </div>

                                <div>
                                    <label for="guest_phone" class="block text-sm font-semibold text-slate-950">Phone *</label>
                                    <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" required>
                                </div>

                                <div>
                                    <label for="travel_date" class="block text-sm font-semibold text-slate-950">Travel Date *</label>
                                    <input type="date" id="travel_date" name="travel_date" value="{{ old('travel_date') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required>
                                </div>

                                <div>
                                    <label for="number_of_travelers" class="block text-sm font-semibold text-slate-950">Number of Travelers *</label>
                                    <input type="number" id="number_of_travelers" name="number_of_travelers" value="{{ old('number_of_travelers', 1) }}" min="1" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required>
                                </div>

                                <div>
                                    <label for="special_requests" class="block text-sm font-semibold text-slate-950">Special Requests</label>
                                    <textarea id="special_requests" name="special_requests" rows="3" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none" placeholder="Any special requests..."></textarea>
                                </div>

                                <button type="submit" class="w-full rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-lg hover:bg-green-700 hover:shadow-xl transition-all active:scale-95">
                                    Submit Booking
                                </button>
                            </form>
                            </x-glass-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
