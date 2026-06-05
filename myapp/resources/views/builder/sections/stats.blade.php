@php
    $items = $c['items'] ?? [];
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

        @if(count($items))
            <dl class="grid gap-8 text-center {{ count($items) <= 2 ? 'grid-cols-2' : (count($items) <= 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2 lg:grid-cols-4') }}">
                @foreach($items as $stat)
                    <div class="flex flex-col items-center gap-2 p-6 rounded-2xl bg-white shadow-sm border border-slate-100">
                        @if(!empty($stat['icon']))
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] mb-1">
                                <x-dynamic-component :component="$stat['icon']" class="w-5 h-5" />
                            </div>
                        @endif
                        <dt class="text-4xl font-black text-[var(--color-primary)]">{{ $stat['number'] ?? '' }}</dt>
                        <dd class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ $stat['label'] ?? '' }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</section>
