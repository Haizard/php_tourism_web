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
                <div class="lg:col-span-2 space-y-8">
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

                    {{-- ===== TRIPADVISOR-STYLE REVIEWS SECTION ===== --}}
                    <div id="reviews" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                        {{-- Header --}}
                        <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#00aa6c]/10">
                                <svg class="w-5 h-5 text-[#00aa6c]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-950">Traveler Reviews</h2>
                                <p class="text-sm text-slate-500">Real reviews from real travelers</p>
                            </div>
                        </div>

                        @if($reviewCount > 0)
                        {{-- Rating Summary --}}
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8">
                                {{-- Overall Score --}}
                                <div class="text-center shrink-0">
                                    <div class="text-6xl font-black text-slate-950 leading-none">{{ number_format($avgRating, 1) }}</div>
                                    <div class="flex items-center justify-center gap-0.5 mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($avgRating))
                                                <svg class="w-5 h-5 text-[#00aa6c]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @elseif($i == ceil($avgRating) && $avgRating != floor($avgRating))
                                                <svg class="w-5 h-5 text-[#00aa6c]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" opacity="0.4"/></svg>
                                            @else
                                                <svg class="w-5 h-5 text-slate-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-sm text-slate-500 mt-1">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
                                    <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-[#00aa6c] px-3 py-1">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs font-bold text-white">
                                            @if($avgRating >= 4.5) Excellent
                                            @elseif($avgRating >= 4.0) Very Good
                                            @elseif($avgRating >= 3.0) Good
                                            @elseif($avgRating >= 2.0) Fair
                                            @else Poor
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                {{-- Rating Bars --}}
                                <div class="flex-1 w-full space-y-2">
                                    @for($stars = 5; $stars >= 1; $stars--)
                                        @php $count = $ratingCounts[$stars] ?? 0; $pct = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0; @endphp
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1 w-16 shrink-0">
                                                <span class="text-sm font-medium text-slate-700">{{ $stars }}</span>
                                                <svg class="w-3.5 h-3.5 text-[#00aa6c]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            </div>
                                            <div class="flex-1 h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-[#00aa6c] rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-sm text-slate-500 w-8 text-right shrink-0">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Individual Reviews --}}
                        <div class="divide-y divide-slate-100">
                            @foreach($reviews as $review)
                            <div class="px-8 py-6">
                                <div class="flex items-start gap-4">
                                    {{-- Avatar --}}
                                    @if($review->author_image)
                                        <img src="{{ asset('storage/' . $review->author_image) }}" alt="{{ $review->author_name }}" class="w-12 h-12 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent)]/70 flex items-center justify-center shrink-0">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($review->author_name, 0, 1)) }}</span>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="font-bold text-slate-950">{{ $review->author_name }}</span>
                                            @if($review->author_title)
                                                <span class="text-sm text-slate-500">{{ $review->author_title }}</span>
                                            @endif
                                            @if($review->traveler_type)
                                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $review->travelerTypeLabel() }}</span>
                                            @endif
                                        </div>

                                        {{-- Stars + Date --}}
                                        <div class="flex flex-wrap items-center gap-3 mt-1">
                                            <div class="flex items-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-[#00aa6c]' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endfor
                                            </div>
                                            @if($review->visit_date)
                                                <span class="text-xs text-slate-400">Visited {{ $review->visit_date->format('F Y') }}</span>
                                            @endif
                                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>

                                        {{-- Review Title --}}
                                        @if($review->review_title)
                                            <h4 class="font-semibold text-slate-950 mt-3">{{ $review->review_title }}</h4>
                                        @endif

                                        {{-- Content --}}
                                        <p class="text-slate-600 mt-2 leading-relaxed">{{ $review->content }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        {{-- Empty State --}}
                        <div class="px-8 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="font-semibold text-slate-950 text-lg">No reviews yet</h3>
                            <p class="text-slate-500 mt-1">Be the first to share your experience on this tour!</p>
                        </div>
                        @endif

                        {{-- Write a Review CTA --}}
                        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                            <button
                                onclick="document.getElementById('write-review-form').scrollIntoView({behavior:'smooth'})"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#00aa6c] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#008f5a] transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                Write a Review
                            </button>
                        </div>
                    </div>

                    {{-- ===== REVIEW SUBMISSION FORM ===== --}}
                    <div id="write-review-form" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[var(--color-accent)]/10">
                                <svg class="w-5 h-5 text-[var(--color-accent)]" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-950">Write a Review</h2>
                                <p class="text-sm text-slate-500">Share your experience to help other travelers</p>
                            </div>
                        </div>

                        <div class="px-8 py-6">
                            @if(session('review_success'))
                                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <p class="text-green-700 font-medium">{{ session('review_success') }}</p>
                                </div>
                            @endif

                            @if($errors->has('rating') || $errors->has('author_name') || $errors->has('content') || $errors->has('review_title'))
                                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                                    <p class="text-red-700 font-semibold mb-2">Please fix the following:</p>
                                    <ul class="space-y-1 text-sm text-red-600">
                                        @foreach($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form
                                action="{{ route('review.store', ['locale' => $currentLocale, 'tour' => $tour->id]) }}"
                                method="POST"
                                class="space-y-6">
                                @csrf

                                {{-- Star Rating Selector --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-950 mb-3">Your Rating *</label>
                                    <div class="flex items-center gap-1" x-data="{ rating: {{ old('rating', 0) }}, hovered: 0 }">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            @mouseenter="hovered = {{ $i }}"
                                            @mouseleave="hovered = 0"
                                            @click="rating = {{ $i }}"
                                            class="transition-transform hover:scale-110 focus:outline-none">
                                            <svg
                                                class="w-10 h-10 transition-colors"
                                                :class="(hovered >= {{ $i }} || rating >= {{ $i }}) ? 'text-[#00aa6c]' : 'text-slate-200'"
                                                fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                        @endfor
                                        <input type="hidden" name="rating" :value="rating">
                                        <span class="ml-3 text-sm text-slate-500" x-text="['', 'Terrible', 'Poor', 'Average', 'Very Good', 'Excellent'][rating] || 'Select a rating'"></span>
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-6">
                                    {{-- Name --}}
                                    <div>
                                        <label for="author_name" class="block text-sm font-semibold text-slate-950 mb-1.5">Your Name *</label>
                                        <input
                                            type="text"
                                            id="author_name"
                                            name="author_name"
                                            value="{{ old('author_name') }}"
                                            placeholder="John Smith"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition-all"
                                            required>
                                    </div>

                                    {{-- Traveler Type --}}
                                    <div>
                                        <label for="traveler_type" class="block text-sm font-semibold text-slate-950 mb-1.5">Type of Traveler</label>
                                        <select
                                            id="traveler_type"
                                            name="traveler_type"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-950 focus:border-[var(--color-accent)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition-all">
                                            <option value="">Select (optional)</option>
                                            <option value="solo" {{ old('traveler_type') == 'solo' ? 'selected' : '' }}>🧳 Solo Traveler</option>
                                            <option value="couple" {{ old('traveler_type') == 'couple' ? 'selected' : '' }}>💑 Couple</option>
                                            <option value="family" {{ old('traveler_type') == 'family' ? 'selected' : '' }}>👨‍👩‍👧 Family</option>
                                            <option value="friends" {{ old('traveler_type') == 'friends' ? 'selected' : '' }}>👫 Friends</option>
                                            <option value="business" {{ old('traveler_type') == 'business' ? 'selected' : '' }}>💼 Business</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-6">
                                    {{-- Review Title --}}
                                    <div>
                                        <label for="review_title" class="block text-sm font-semibold text-slate-950 mb-1.5">Review Title *</label>
                                        <input
                                            type="text"
                                            id="review_title"
                                            name="review_title"
                                            value="{{ old('review_title') }}"
                                            placeholder="e.g. Amazing Safari Experience!"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition-all"
                                            required>
                                    </div>

                                    {{-- Visit Date --}}
                                    <div>
                                        <label for="visit_date" class="block text-sm font-semibold text-slate-950 mb-1.5">When Did You Visit?</label>
                                        <input
                                            type="month"
                                            id="visit_date"
                                            name="visit_date"
                                            value="{{ old('visit_date') ? \Carbon\Carbon::parse(old('visit_date'))->format('Y-m') : '' }}"
                                            max="{{ now()->format('Y-m') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-950 focus:border-[var(--color-accent)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition-all">
                                    </div>
                                </div>

                                {{-- Review Body --}}
                                <div>
                                    <label for="content" class="block text-sm font-semibold text-slate-950 mb-1.5">Your Review *</label>
                                    <textarea
                                        id="content"
                                        name="content"
                                        rows="5"
                                        placeholder="Share details of your experience: what you liked, what could be better, tips for other travelers..."
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition-all resize-none"
                                        required
                                        minlength="20">{{ old('content') }}</textarea>
                                    <p class="text-xs text-slate-400 mt-1">Minimum 20 characters</p>
                                </div>

                                {{-- Disclaimer + Submit --}}
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pt-2">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#00aa6c] px-8 py-3 font-semibold text-white shadow-sm hover:bg-[#008f5a] active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                        Submit Review
                                    </button>
                                    <p class="text-xs text-slate-400">Reviews are moderated and will appear after approval.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ===== END REVIEWS SECTION ===== --}}

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

                            {{-- Rating Badge in Sidebar --}}
                            @if($reviewCount > 0)
                                <a href="#reviews" class="flex items-center gap-3 rounded-xl bg-[#00aa6c]/8 border border-[#00aa6c]/20 p-3 hover:bg-[#00aa6c]/12 transition-colors group">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= round($avgRating) ? 'text-[#00aa6c]' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-950">{{ number_format($avgRating, 1) }} / 5</p>
                                        <p class="text-xs text-slate-500">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 ml-auto group-hover:text-[#00aa6c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
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

                            @if ($errors->any() && !$errors->has('rating') && !$errors->has('author_name') && !$errors->has('content') && !$errors->has('review_title'))
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
