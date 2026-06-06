<x-layouts.app :title="$tour->title . ' | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$tour->excerpt ?? 'Explore this amazing tour package.'">

    @if(!empty($tourTemplate?->custom_css))
    <style>
        {!! $tourTemplate->custom_css !!}
    </style>
    @endif

    @php
        $headerStyle = $tourTemplate?->header_style ?? 'default';
        $layout      = $tourTemplate?->layout ?? 'default';
        $cardStyle   = $tourTemplate?->card_style ?? 'default';

        $sc = $tourTemplate?->sections_config ?? [];
        $mainSections    = $sc['main']    ?? [
            ['key' => 'overview',          'is_active' => true],
            ['key' => 'itinerary',         'is_active' => true],
            ['key' => 'included_services', 'is_active' => true],
            ['key' => 'excluded_services', 'is_active' => true],
        ];
        $sidebarSections = $sc['sidebar'] ?? [
            ['key' => 'tour_details_card', 'is_active' => true],
            ['key' => 'booking_form',      'is_active' => true],
        ];
        $belowSections   = $sc['below']   ?? [
            ['key' => 'related_tours', 'is_active' => true],
        ];

        $cardClass = match($cardStyle) {
            'bordered' => 'border border-slate-200 rounded-2xl p-6 bg-white',
            'shadow'   => 'rounded-2xl p-6 bg-white shadow-xl',
            'glass'    => 'rounded-2xl p-6 bg-white/30 backdrop-blur border border-white/20',
            default    => 'rounded-2xl p-6 bg-white/60 backdrop-blur border border-slate-100 shadow-sm',
        };
    @endphp

    @if($headerStyle === 'minimal')
        <div class="border-b border-slate-200 bg-white py-12 px-6">
            <div class="mx-auto max-w-6xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Tour Details</p>
                <h1 class="text-4xl font-black text-slate-900">{{ $tour->title }}</h1>
                @if($tour->excerpt)<p class="mt-3 text-lg text-slate-500">{{ $tour->excerpt }}</p>@endif
            </div>
        </div>
    @elseif($headerStyle === 'split')
        <div class="bg-slate-900 py-16 px-6">
            <div class="mx-auto max-w-6xl grid gap-10 lg:grid-cols-2 items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Tour Details</p>
                    <h1 class="text-4xl font-black text-white">{{ $tour->title }}</h1>
                    @if($tour->excerpt)<p class="mt-3 text-lg text-slate-300">{{ $tour->excerpt }}</p>@endif
                </div>
                @if($tour->featured_image)
                    <img src="{{ asset('storage/'.$tour->featured_image) }}" class="rounded-2xl w-full h-64 object-cover shadow-xl" alt="{{ $tour->title }}" />
                @endif
            </div>
        </div>
    @elseif($headerStyle === 'overlay')
        <div class="relative overflow-hidden min-h-[340px] flex items-end">
            <div class="absolute inset-0" style="background-image: url({{ $tour->featured_image ? asset('storage/'.$tour->featured_image) : asset('images/creation-africa/tanzania-lodge-safaris.jpg') }}); background-size:cover; background-position:center;"></div>
            <div class="absolute inset-0 bg-slate-950/70"></div>
            <div class="relative mx-auto max-w-6xl w-full px-6 pb-12">
                <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Tour Details</p>
                <h1 class="text-4xl font-black text-white">{{ $tour->title }}</h1>
                @if($tour->excerpt)<p class="mt-3 text-lg text-white/80">{{ $tour->excerpt }}</p>@endif
            </div>
        </div>
    @else
        <x-page-header
            :title="$tour->title"
            :subtitle="$tour->excerpt"
            :image="$tour->featured_image ? asset('storage/' . $tour->featured_image) : asset('images/creation-africa/tanzania-lodge-safaris.jpg')"
            eyebrow="Tour Details"
            ctaText="Book This Tour"
        />
    @endif

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto {{ $layout === 'centered' ? 'max-w-3xl' : 'max-w-6xl' }}">
            <div class="{{ $layout === 'full_width' ? '' : 'grid gap-12 lg:grid-cols-3' }}">

                {{-- ── Main Content Column ── --}}
                <div class="{{ $layout === 'full_width' ? '' : 'lg:col-span-2' }}">
                    <div class="{{ $cardClass }} space-y-12">

                        @foreach($mainSections as $ms)
                            @if($ms['is_active'] ?? true)
                                @if($ms['key'] === 'overview')
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-950 mb-4">Tour Overview</h2>
                                        <div class="prose prose-slate max-w-none">{!! $tour->content !!}</div>
                                    </div>

                                @elseif($ms['key'] === 'itinerary' && $tour->itinerary)
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

                                @elseif($ms['key'] === 'included_services' && $tour->included_services)
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-950 mb-4">Included Services</h2>
                                        <ul class="space-y-2">
                                            @foreach($tour->included_services as $service => $details)
                                                <li class="flex items-start gap-3">
                                                    <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    <div>
                                                        <p class="font-semibold text-slate-950">{{ $service }}</p>
                                                        <p class="text-slate-600 text-sm">{{ $details }}</p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                @elseif($ms['key'] === 'excluded_services' && $tour->excluded_services)
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-950 mb-4">Excluded Services</h2>
                                        <ul class="space-y-2">
                                            @foreach($tour->excluded_services as $service => $details)
                                                <li class="flex items-start gap-3">
                                                    <svg class="h-5 w-5 text-slate-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                    <div>
                                                        <p class="font-semibold text-slate-950">{{ $service }}</p>
                                                        <p class="text-slate-600 text-sm">{{ $details }}</p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        @endforeach

                        <div class="border-t border-slate-200 pt-8">
                            <a href="{{ url('/' . $currentLocale . '/tours') }}" class="inline-flex items-center gap-2 text-[var(--color-accent)] font-semibold hover:opacity-70 transition-opacity">
                                ← Back to Tours
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Sidebar Column ── --}}
                @if($layout !== 'full_width')
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        @foreach($sidebarSections as $ss)
                            @if($ss['is_active'] ?? true)
                                @if($ss['key'] === 'tour_details_card')
                                    <div class="sticky top-8">
                                        <div class="{{ $cardClass }} space-y-6">
                                            <h3 class="text-lg font-semibold text-slate-950">Tour Details</h3>
                                            @if($tour->price)
                                                <div class="rounded-xl bg-[var(--color-accent)]/10 p-4">
                                                    <p class="text-sm text-slate-600">Price per person</p>
                                                    <p class="mt-2 text-3xl font-bold text-[var(--color-accent)]">${{ number_format($tour->discount_price ?? $tour->price, 2) }}</p>
                                                    @if($tour->discount_price)
                                                        <p class="mt-2 text-sm line-through text-slate-500">Regular: ${{ number_format($tour->price, 2) }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="space-y-4">
                                                @if($tour->duration)<div><p class="text-sm font-semibold text-slate-950">Duration</p><p class="mt-1 text-slate-600">{{ $tour->duration }}</p></div>@endif
                                                @if($tour->destination)<div><p class="text-sm font-semibold text-slate-950">Destination</p><p class="mt-1 text-slate-600">{{ $tour->destination->name }}</p></div>@endif
                                                @if($tour->category)<div><p class="text-sm font-semibold text-slate-950">Category</p><p class="mt-1 text-slate-600">{{ $tour->category->name }}</p></div>@endif
                                            </div>
                                        </div>
                                    </div>

                                @elseif($ss['key'] === 'booking_form')
                                    <div class="{{ $cardClass }} space-y-6">
                                        <h3 class="text-lg font-semibold text-slate-950">Book This Tour</h3>
                                        @if ($errors->any())
                                            <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                                                <p class="text-red-700 font-semibold">Please fix the errors below:</p>
                                                <ul class="mt-2 space-y-1 text-sm text-red-600">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>
                                            </div>
                                        @endif
                                        @if (session('success'))
                                            <div class="rounded-lg bg-green-50 p-4 border border-green-200"><p class="text-green-700">{{ session('success') }}</p></div>
                                        @endif
                                        <form action="{{ route('booking.store', ['locale' => $currentLocale, 'tour' => $tour->id]) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div><label class="block text-sm font-semibold text-slate-950">Full Name *</label><input type="text" name="guest_name" value="{{ old('guest_name') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required></div>
                                            <div><label class="block text-sm font-semibold text-slate-950">Email *</label><input type="email" name="guest_email" value="{{ old('guest_email') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required></div>
                                            <div><label class="block text-sm font-semibold text-slate-950">Phone *</label><input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required></div>
                                            <div><label class="block text-sm font-semibold text-slate-950">Travel Date *</label><input type="date" name="travel_date" value="{{ old('travel_date') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required></div>
                                            <div><label class="block text-sm font-semibold text-slate-950">Number of Travelers *</label><input type="number" name="number_of_travelers" value="{{ old('number_of_travelers', 1) }}" min="1" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" required></div>
                                            <div><label class="block text-sm font-semibold text-slate-950">Special Requests</label><textarea name="special_requests" rows="3" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-950 focus:border-[var(--color-accent)] focus:outline-none" placeholder="Any special requests..."></textarea></div>
                                            <button type="submit" class="w-full rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-lg hover:bg-green-700 transition-all active:scale-95">Submit Booking</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ── Below-fold Sections ── --}}
    @foreach($belowSections as $bs)
        @if($bs['is_active'] ?? true)
            @if($bs['key'] === 'related_tours')
                @php
                    $relatedTours = \App\Models\Tour::where('is_published', true)
                        ->where('id', '!=', $tour->id)
                        ->when($tour->category_id, fn($q) => $q->where('category_id', $tour->category_id))
                        ->limit(3)->get();
                @endphp
                @if($relatedTours->count())
                <section class="px-6 pb-20 lg:px-8">
                    <div class="mx-auto max-w-6xl">
                        <h2 class="text-2xl font-bold text-slate-900 mb-8">Related Tours</h2>
                        <div class="grid gap-6 sm:grid-cols-3">
                            @foreach($relatedTours as $related)
                                <a href="{{ url("/{$currentLocale}/tours/{$related->slug}") }}" class="group block rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100 transition hover:-translate-y-1">
                                    @if($related->featured_image)<img src="{{ asset('storage/'.$related->featured_image) }}" class="w-full h-48 object-cover group-hover:scale-105 transition" alt="{{ $related->title }}" />@endif
                                    <div class="p-4"><h3 class="font-bold text-slate-900">{{ $related->title }}</h3><p class="text-sm text-[var(--color-primary)] font-semibold mt-1">${{ number_format($related->price, 0) }}</p></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif
            @endif
        @endif
    @endforeach

</x-layouts.app>
