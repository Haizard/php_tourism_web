@php
    $cols = $c['columns'] ?? '3';
    $colClass = match($cols) {
        '2' => 'grid-cols-2',
        '4' => 'grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-2 lg:grid-cols-3',
    };
    $images = $c['images'] ?? [];
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

        @if(count($images))
            <div class="grid gap-4 {{ $colClass }}">
                @foreach($images as $img)
                    @if(!empty($img['image']))
                        <div class="group relative overflow-hidden rounded-xl">
                            <img src="{{ asset('storage/' . $img['image']) }}"
                                 alt="{{ $img['caption'] ?? '' }}"
                                 class="w-full h-60 object-cover transition duration-300 group-hover:scale-105" />
                            @if(!empty($img['caption']))
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 opacity-0 group-hover:opacity-100 transition">
                                    <p class="text-white text-sm font-medium">{{ $img['caption'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
