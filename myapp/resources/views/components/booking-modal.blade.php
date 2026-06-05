{{--
    Global Booking Modal
    Triggered via: window.dispatchEvent(new CustomEvent('open-booking-modal', { detail: { tourId, tourTitle, tourPrice } }))
    When tourId is null, shows a tour selector pulled from the DB.
--}}
@php
    $locale      = $currentLocale ?? app()->getLocale();
    $baseUrl     = url('/' . $locale . '/tours');
    $bookingBase = url('/' . $locale . '/bookings');

    try {
        $availableTours = \App\Models\Tour::where('is_published', true)
            ->select('id', 'title', 'price', 'discount_price')
            ->orderBy('title')
            ->get();
    } catch (\Throwable $e) {
        $availableTours = collect();
    }
@endphp

<div
    x-data="{
        open: false,
        loading: false,
        success: false,
        tourId: null,
        tourTitle: '',
        tourPrice: 0,
        travelers: 1,
        selectedTourId: null,
        get total() {
            return this.tourPrice > 0 ? (this.tourPrice * this.travelers).toFixed(2) : 0;
        },
        get formAction() {
            const id = this.tourId ?? this.selectedTourId;
            return id ? '{{ $bookingBase }}/' + id : '#';
        },
        init() {
            window.addEventListener('open-booking-modal', (e) => {
                this.tourId       = e.detail.tourId    ?? null;
                this.tourTitle    = e.detail.tourTitle ?? 'Plan Your Trip';
                this.tourPrice    = parseFloat(e.detail.tourPrice ?? 0);
                this.selectedTourId = this.tourId;
                this.travelers    = 1;
                this.success      = false;
                this.loading      = false;
                this.open         = true;
                this.$nextTick(() => this.$refs.nameInput?.focus());
            });
        },
        handleTourChange(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            this.selectedTourId = opt.value || null;
            this.tourPrice      = parseFloat(opt.dataset.price ?? 0);
        },
        close() { this.open = false; }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    @keydown.escape.window="close()"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm"
        @click="close()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Modal panel --}}
    <div
        class="relative w-full max-w-lg rounded-2xl border border-white/20 bg-white shadow-2xl overflow-hidden"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
    >
        {{-- Header --}}
        <div class="bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-white/70">Book Your Adventure</p>
                    <h2 class="mt-1 text-xl font-bold text-white" x-text="tourTitle"></h2>
                </div>
                <button @click="close()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- Price preview --}}
            <div x-show="total > 0" class="mt-4 flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex items-center gap-2 text-white text-sm">
                    <span class="font-medium">Estimated Total:</span>
                    <span class="text-lg font-bold">$<span x-text="total"></span></span>
                    <span class="text-white/60">(<span x-text="travelers"></span> × $<span x-text="tourPrice.toFixed(2)"></span>)</span>
                </div>
            </div>
        </div>

        {{-- Success state --}}
        <div x-show="success" class="p-10 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-950">Booking Submitted!</h3>
            <p class="mt-2 text-slate-500 text-sm">We've received your request and will contact you within 24 hours to confirm your booking.</p>
            <button @click="close()" class="mt-6 rounded-xl bg-[var(--color-accent)] px-8 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                Close
            </button>
        </div>

        {{-- Booking form --}}
        <form
            x-show="!success"
            method="POST"
            :action="formAction"
            @submit.prevent="
                if (!selectedTourId && !tourId) { return; }
                loading = true;
                $el.submit();
            "
            class="max-h-[70vh] overflow-y-auto p-6 space-y-4"
        >
            @csrf

            {{-- Tour selector — shown when modal is opened without a specific tour --}}
            @if($availableTours->isNotEmpty())
                <div x-show="!tourId">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Select a Tour *</label>
                    <select
                        name="_selected_tour"
                        @change="handleTourChange($el)"
                        required
                        x-bind:required="!tourId"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition"
                    >
                        <option value="">— Choose a tour —</option>
                        @foreach($availableTours as $t)
                            <option
                                value="{{ $t->id }}"
                                data-price="{{ $t->discount_price ?? $t->price }}"
                            >{{ $t->title }}{{ $t->price ? ' — $' . number_format($t->discount_price ?? $t->price, 0) . '/person' : '' }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-400">Or <a href="{{ $baseUrl }}" class="underline text-[var(--color-accent)]">browse all tours</a> to see full details before booking.</p>
                </div>
            @else
                <div x-show="!tourId" class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-700">
                    Please <a href="{{ $baseUrl }}" class="font-semibold underline">browse our tours</a> and click "Book This Tour" on any tour page to start a booking.
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Full Name *</label>
                    <input x-ref="nameInput" type="text" name="guest_name" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition"
                        placeholder="Jane Smith">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Phone *</label>
                    <input type="tel" name="guest_phone" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition"
                        placeholder="+1 555 0100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Email *</label>
                <input type="email" name="guest_email" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition"
                    placeholder="jane@example.com">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Travel Date *</label>
                    <input type="date" name="travel_date" required
                        :min="new Date(Date.now() + 86400000).toISOString().split('T')[0]"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Travelers *</label>
                    <input type="number" name="number_of_travelers" min="1" max="50" required
                        x-model="travelers"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Special Requests</label>
                <textarea name="special_requests" rows="2"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition resize-none"
                    placeholder="Dietary needs, accessibility requirements, special occasions…"></textarea>
            </div>

            <button type="submit"
                :disabled="loading || (!tourId && !selectedTourId)"
                class="w-full rounded-xl bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] py-3 text-sm font-bold text-white shadow-lg transition hover:opacity-90 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!loading">Submit Booking Request</span>
                <span x-show="loading" class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Submitting…
                </span>
            </button>

            <p class="text-center text-xs text-slate-400">No payment now — our team confirms availability first.</p>
        </form>
    </div>
</div>
