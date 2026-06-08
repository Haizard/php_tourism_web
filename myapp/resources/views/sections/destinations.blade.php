@php
    $bg = $sectionBackgrounds['destinations'] ?? null;
    $hasBg = $bg && $bg->bg_type !== 'none' && $bg->bg_value;
    $bgStyle = $hasBg ? $bg->inline_style : '';
    $overlayStyle = $hasBg ? $bg->overlay_style : '';
    $sectionTextClass = $bg ? $bg->text_class : 'text-slate-950';
    $headingClass = ($bg && $bg->text_color === 'light') ? 'text-white' : 'text-slate-950';
    $mutedClass = ($bg && $bg->text_color === 'light') ? 'text-white/75' : 'text-slate-600';
    
    // Fetch destinations from database
    $destinations = \App\Models\Destination::where('is_published', true)
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();
    
    $hasDestinations = $destinations->count() > 0;
    
    // Static fallback destinations
    $staticDestinations = [
        [
            'name' => 'Ngorongoro Crater',
            'country' => 'Tanzania',
            'description' => 'A UNESCO World Heritage site, the Ngorongoro Crater is the world\'s largest inactive volcanic caldera.',
            'image' => 'images/creation-africa/ngorongor-crater-banner.jpg',
            'slug' => null
        ],
        [
            'name' => 'Serengeti National Park',
            'country' => 'Tanzania',
            'description' => 'Famous for the annual Great Migration of over 1.5 million wildebeest and 250,000 zebra.',
            'image' => 'images/creation-africa/hero1.jpg',
            'slug' => null
        ],
        [
            'name' => 'Maasai Mara',
            'country' => 'Kenya',
            'description' => 'A national reserve known for its exceptional population of lions, leopards, and cheetahs.',
            'image' => 'images/creation-africa/cheetah.jpg',
            'slug' => null
        ],
        [
            'name' => 'Mount Kilimanjaro',
            'country' => 'Tanzania',
            'description' => 'Africa\'s highest mountain and the world\'s highest free-standing mountain at 5,895 meters.',
            'image' => 'images/creation-africa/safari.jpg',
            'slug' => null
        ],
        [
            'name' => 'Zanzibar Archipelago',
            'country' => 'Tanzania',
            'description' => 'A tropical paradise with pristine beaches, crystal-clear waters, and rich cultural heritage.',
            'image' => 'images/creation-africa/lion.jpg',
            'slug' => null
        ],
        [
            'name' => 'Amboseli National Park',
            'country' => 'Kenya',
            'description' => 'Famous for its large elephant herds and stunning views of Mount Kilimanjaro.',
            'image' => 'images/creation-africa/elephant.jpg',
            'slug' => null
        ],
    ];
    
    $displayDestinations = $hasDestinations ? $destinations : collect($staticDestinations);
@endphp

<section class="px-6 py-16 lg:px-8 relative {{ $sectionTextClass }}" style="{{ $bgStyle }}">
    @if ($overlayStyle)
        <div class="absolute inset-0 pointer-events-none" style="{{ $overlayStyle }}"></div>
    @endif
    <div class="relative mx-auto max-w-7xl">
        <div class="page-section-header text-center">
            <span class="badge-pill bg-[var(--color-accent)]/15 text-[var(--color-accent)]">Top Destinations</span>
            <h2 class="mt-4 text-4xl font-black {{ $headingClass }}">Experience the World's Top Destinations</h2>
            <p class="mt-3 mx-auto max-w-2xl {{ $mutedClass }}">Discover inspiring locations, iconic landmarks, and unforgettable journeys with visuals that bring every destination to life.</p>
        </div>

        <div class="grid gap-6 mt-10 lg:grid-cols-[1.15fr_0.85fr]">
            <!-- Left column: 4 destinations in 2x2 grid -->
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach($displayDestinations->take(4) as $destination)
                    <article class="top-destination-card overflow-hidden rounded-[2rem] relative group cursor-pointer transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl">
                        <img 
                            src="{{ $hasDestinations ? asset('storage/' . ($destination->featured_image ?? 'images/placeholder.jpg')) : asset($destination['image']) }}" 
                            alt="{{ $hasDestinations ? $destination->name : $destination['name'] }}" 
                            loading="lazy"
                            class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-pink-300">
                                {{ $hasDestinations ? 'Tanzania' : ($destination['country'] ?? 'Tanzania') }}
                            </p>
                            <h3 class="mt-2 text-xl font-black leading-tight">
                                {{ $hasDestinations ? $destination->name : $destination['name'] }}
                            </h3>
                            @if($hasDestinations && $destination->description)
                                <p class="mt-2 text-sm text-slate-200/90 line-clamp-2">
                                    {{ Str::limit($destination->description, 100) }}
                                </p>
                            @elseif(!$hasDestinations && isset($destination['description']))
                                <p class="mt-2 text-sm text-slate-200/90 line-clamp-2">
                                    {{ Str::limit($destination['description'], 100) }}
                                </p>
                            @endif
                            <div class="mt-3 flex items-center text-sm font-semibold text-pink-300 group-hover:text-pink-200 transition-colors">
                                <span>Explore</span>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                        @if($hasDestinations && isset($destination->slug) && $destination->slug)
                            <a href="{{ route('destination.show', ['locale' => app()->currentLocale(), 'slug' => $destination->slug]) }}" class="absolute inset-0 z-10"></a>
                        @endif
                    </article>
                @endforeach
            </div>

            <!-- Right column: 2 destinations stacked -->
            <div class="grid gap-6">
                @foreach($displayDestinations->skip(4)->take(2) as $destination)
                    <article class="top-destination-card overflow-hidden rounded-[2rem] relative group cursor-pointer transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl h-full">
                        <img 
                            src="{{ $hasDestinations ? asset('storage/' . ($destination->featured_image ?? 'images/placeholder.jpg')) : asset($destination['image']) }}" 
                            alt="{{ $hasDestinations ? $destination->name : $destination['name'] }}" 
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-pink-300">
                                {{ $hasDestinations ? 'Tanzania' : ($destination['country'] ?? 'Tanzania') }}
                            </p>
                            <h3 class="mt-2 text-xl font-black leading-tight">
                                {{ $hasDestinations ? $destination->name : $destination['name'] }}
                            </h3>
                            @if($hasDestinations && $destination->description)
                                <p class="mt-2 text-sm text-slate-200/90 line-clamp-2">
                                    {{ Str::limit($destination->description, 100) }}
                                </p>
                            @elseif(!$hasDestinations && isset($destination['description']))
                                <p class="mt-2 text-sm text-slate-200/90 line-clamp-2">
                                    {{ Str::limit($destination['description'], 100) }}
                                </p>
                            @endif
                            <div class="mt-3 flex items-center text-sm font-semibold text-pink-300 group-hover:text-pink-200 transition-colors">
                                <span>Explore</span>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                        @if($hasDestinations && isset($destination->slug) && $destination->slug)
                            <a href="{{ route('destination.show', ['locale' => app()->currentLocale(), 'slug' => $destination->slug]) }}" class="absolute inset-0 z-10"></a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
