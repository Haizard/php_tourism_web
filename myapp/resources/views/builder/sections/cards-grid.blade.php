@php
    $cols = $c['columns'] ?? '3';
    $colClass = match($cols) {
        '2' => 'sm:grid-cols-2',
        '4' => 'sm:grid-cols-2 lg:grid-cols-4',
        default => 'sm:grid-cols-2 lg:grid-cols-3',
    };
    $cardStyle = $c['card_style'] ?? 'default';
    $cardClass = match($cardStyle) {
        'bordered' => 'border border-slate-200 rounded-2xl overflow-hidden bg-white',
        'shadow'   => 'rounded-2xl overflow-hidden bg-white shadow-lg',
        'glass'    => 'rounded-2xl overflow-hidden bg-white/30 backdrop-blur border border-white/20',
        default    => 'rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100',
    };
    $cards = $c['cards'] ?? [];
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

        @if(count($cards))
            <div class="grid gap-6 {{ $colClass }}">
                @foreach($cards as $card)
                    <div class="{{ $cardClass }}">
                        @if(!empty($card['image']))
                            <img src="{{ asset('storage/' . $card['image']) }}"
                                 alt="{{ $card['title'] ?? '' }}"
                                 class="w-full h-52 object-cover" />
                        @endif
                        <div class="p-6">
                            @if(!empty($card['badge']))
                                <span class="inline-block text-xs font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">{{ $card['badge'] }}</span>
                            @endif
                            @if(!empty($card['title']))
                                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $card['title'] }}</h3>
                            @endif
                            @if(!empty($card['description']))
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $card['description'] }}</p>
                            @endif
                            @if(!empty($card['link']))
                                <a href="{{ $card['link'] }}"
                                   class="mt-4 inline-flex items-center text-sm font-semibold text-[var(--color-primary)] hover:underline">
                                    {{ $card['btn_text'] ?? 'Learn More' }} →
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
