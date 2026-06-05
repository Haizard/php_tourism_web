@php
    $imagePosition = $c['image_position'] ?? 'left';
    $orderClass = $imagePosition === 'right' ? 'lg:order-last' : '';
@endphp

<section class="py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
            @if(!empty($c['image']))
                <div class="{{ $orderClass }}">
                    <img src="{{ asset('storage/' . $c['image']) }}"
                         alt="{{ $c['title'] ?? '' }}"
                         class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]" />
                </div>
            @endif

            <div>
                @if(!empty($c['title']))
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl mb-6">
                        {{ $c['title'] }}
                    </h2>
                @endif
                @if(!empty($c['body']))
                    <div class="prose prose-lg text-slate-600 max-w-none">
                        {!! $c['body'] !!}
                    </div>
                @endif
                @if(!empty($c['btn_text']))
                    <div class="mt-8">
                        <a href="{{ $c['btn_url'] ?? '#' }}"
                           class="inline-flex items-center justify-center rounded-full bg-[var(--color-primary)] px-7 py-3 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:opacity-90">
                            {{ $c['btn_text'] }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
