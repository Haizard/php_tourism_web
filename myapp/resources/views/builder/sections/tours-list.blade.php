@php
    use App\Models\Tour;
    $limit = (int) ($c['limit'] ?? 6);
    $cardStyle = $c['card_style'] ?? 'default';
    $tours = Tour::where('is_published', true)->latest()->limit($limit)->get();
    $cardClass = match($cardStyle) {
        'bordered' => 'border border-slate-200 rounded-2xl overflow-hidden bg-white',
        'minimal'  => 'rounded-xl bg-transparent',
        default    => 'rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100',
    };
@endphp

<section class="py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if(!empty($c['title']) || !empty($c['subtitle']))
            <div class="text-center mb-12">
                @if(!empty($c['title']))
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{{ $c['title'] }}</h2>
                @endif
                @if(!empty($c['subtitle']))
                    <p class="mt-4 text-lg text-slate-500">{{ $c['subtitle'] }}</p>
                @endif
            </div>
        @endif

        @if($tours->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($tours as $tour)
                    <a href="{{ url("/{$currentLocale}/tours/{$tour->slug}") }}" class="{{ $cardClass }} group block transition hover:-translate-y-1 duration-200">
                        @if($tour->featured_image)
                            <div class="overflow-hidden">
                                <img src="{{ asset('storage/' . $tour->featured_image) }}"
                                     alt="{{ $tour->title }}"
                                     class="w-full h-52 object-cover transition duration-300 group-hover:scale-105" />
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 text-base mb-1">{{ $tour->title }}</h3>
                            <p class="text-slate-500 text-sm line-clamp-2">{{ $tour->excerpt }}</p>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="text-[var(--color-primary)] font-bold">${{ number_format($tour->price, 0) }}</span>
                                @if($tour->duration)
                                    <span class="text-slate-400">{{ $tour->duration }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-slate-400">No tours available yet.</p>
        @endif
    </div>
</section>
