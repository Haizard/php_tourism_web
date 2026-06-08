<x-layouts.app :title="$tour->title . ' | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$tour->excerpt ?? 'Explore this amazing tour package.'">
    <x-page-header
        :title="$tour->title"
        :subtitle="$tour->excerpt"
        :image="$tour->featured_image ? asset('storage/' . $tour->featured_image) : asset('images/creation-africa/tanzania-lodge-safaris.jpg')"
        eyebrow="Tour Details"
        ctaText="Book This Tour"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-12 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-8">
                    {{-- ITINERARY SECTION --}}
                    @php
                        $accordionItems = [];
                        if($tour->itinerary) {
                            foreach($tour->itinerary as $day => $activities) {
                                $accordionItems[] = [
                                    'title' => $day,
                                    'icon' => '📅',
                                    'content' => $activities,
                                ];
                            }
                        }
                    @endphp
                    
                    @if($tour->itinerary && count($accordionItems) > 0)
                        <div>
                            <div class="mb-8">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-4xl">📅</span>
                                    <div>
                                        <h2 class="text-3xl lg:text-4xl font-black text-slate-950">Daily Itinerary</h2>
                                        <p class="text-slate-600 mt-1">Experience each day of your journey</p>
                                    </div>
                                </div>
                                <div class="h-1 w-20 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                @foreach($accordionItems as $index => $item)
                                    <details class="group rounded-2xl border-2 border-slate-100 hover:border-[var(--color-primary)]/30 bg-gradient-to-br from-white via-slate-50 to-white hover:from-white hover:to-[var(--color-primary)]/5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                                        <summary class="cursor-pointer select-none px-6 py-5 flex items-center justify-between font-semibold text-slate-950 hover:text-[var(--color-primary)] group-open:bg-gradient-to-r group-open:from-[var(--color-primary)]/5 group-open:to-[var(--color-accent)]/5">
                                            <span class="flex items-center gap-4">
                                                <span class="text-3xl">{{ $item['icon'] }}</span>
                                                <span class="text-lg">{{ $item['title'] }}</span>
                                                <span class="hidden sm:inline-block px-3 py-1 text-xs font-bold rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">Day {{ $index + 1 }}</span>
                                            </span>
                                            <svg class="h-6 w-6 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                        </summary>
                                        <div class="border-t-2 border-slate-100 px-6 py-5 text-slate-600 bg-gradient-to-b from-transparent to-[var(--color-primary)]/2">
                                            <p class="leading-relaxed text-base">{{ $item['content'] }}</p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- INCLUDED SERVICES SECTION --}}
                    @php
                        $includedItems = [];
                        if($tour->included_services) {
                            foreach($tour->included_services as $service => $details) {
                                $includedItems[] = [
                                    'title' => $service,
                                    'content' => $details,
                                ];
                            }
                        }
                    @endphp

                    @if($tour->included_services && count($includedItems) > 0)
                        <div>
                            <div class="mb-8">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-4xl">✅</span>
                                    <div>
                                        <h2 class="text-3xl lg:text-4xl font-black text-slate-950">What's Included</h2>
                                        <p class="text-slate-600 mt-1">Everything covered in your tour package</p>
                                    </div>
                                </div>
                                <div class="h-1 w-20 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                @foreach($includedItems as $item)
                                    <details class="group rounded-2xl border-2 border-green-100 hover:border-green-300 bg-gradient-to-br from-white via-green-50/30 to-white hover:from-white hover:to-green-100/20 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                                        <summary class="cursor-pointer select-none px-6 py-5 flex items-center justify-between font-semibold text-slate-950 hover:text-green-700 group-open:bg-gradient-to-r group-open:from-green-50 group-open:to-emerald-50">
                                            <span class="flex items-center gap-4">
                                                <span class="text-2xl">🎁</span>
                                                <span class="text-lg">{{ $item['title'] }}</span>
                                            </span>
                                            <svg class="h-6 w-6 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                        </summary>
                                        <div class="border-t-2 border-green-100 px-6 py-5 text-slate-600 bg-gradient-to-b from-transparent to-green-50">
                                            <p class="leading-relaxed text-base">{{ $item['content'] }}</p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- EXCLUDED SERVICES SECTION --}}
                    @php
                        $excludedItems = [];
                        if($tour->excluded_services) {
                            foreach($tour->excluded_services as $service => $details) {
                                $excludedItems[] = [
                                    'title' => $service,
                                    'content' => $details,
                                ];
                            }
                        }
                    @endphp

                    @if($tour->excluded_services && count($excludedItems) > 0)
                        <div>
                            <div class="mb-8">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-4xl">⚠️</span>
                                    <div>
                                        <h2 class="text-3xl lg:text-4xl font-black text-slate-950">Not Included</h2>
                                        <p class="text-slate-600 mt-1">Please note these are not covered</p>
                                    </div>
                                </div>
                                <div class="h-1 w-20 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                @foreach($excludedItems as $item)
                                    <details class="group rounded-2xl border-2 border-amber-100 hover:border-amber-300 bg-gradient-to-br from-white via-amber-50/30 to-white hover:from-white hover:to-amber-100/20 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                                        <summary class="cursor-pointer select-none px-6 py-5 flex items-center justify-between font-semibold text-slate-950 hover:text-amber-700 group-open:bg-gradient-to-r group-open:from-amber-50 group-open:to-orange-50">
                                            <span class="flex items-center gap-4">
                                                <span class="text-2xl">⛔</span>
                                                <span class="text-lg">{{ $item['title'] }}</span>
                                            </span>
                                            <svg class="h-6 w-6 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                        </summary>
                                        <div class="border-t-2 border-amber-100 px-6 py-5 text-slate-600 bg-gradient-to-b from-transparent to-amber-50">
                                            <p class="leading-relaxed text-base">{{ $item['content'] }}</p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- TOUR OVERVIEW SECTION --}}
                    <div class="rounded-3xl border-2 border-slate-100 bg-gradient-to-br from-white via-slate-50 to-white p-8 lg:p-10 shadow-lg">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-4xl">📖</span>
                                <div>
                                    <h2 class="text-3xl lg:text-4xl font-black text-slate-950">Tour Overview</h2>
                                    <p class="text-slate-600 mt-1">Complete details about this amazing experience</p>
                                </div>
                            </div>
                            <div class="h-1 w-20 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] rounded-full"></div>
                        </div>
                        <div class="prose prose-lg prose-slate max-w-none
                            [&_h1]:text-2xl [&_h1]:font-black [&_h1]:text-slate-950 [&_h1]:mt-6 [&_h1]:mb-4
                            [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mt-5 [&_h2]:mb-3
                            [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-slate-800
                            [&_p]:text-slate-600 [&_p]:leading-relaxed [&_p]:mb-4
                            [&_ul]:space-y-2 [&_li]:text-slate-600
                            [&_a]:text-[var(--color-primary)] [&_a]:font-semibold [&_a]:hover:underline
                        ">
                            {!! $tour->content !!}
                        </div>
                    </div>

                    {{-- BACK BUTTON --}}
                    <div class="pt-6">
                        <a href="{{ url('/' . $currentLocale . '/tours') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-slate-900 to-slate-700 text-white font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Tours
                        </a>
                    </div>
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
