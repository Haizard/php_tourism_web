@php
    $alignment = $c['alignment'] ?? 'left';
    $alignClass = match($alignment) {
        'center' => 'text-center mx-auto',
        'right'  => 'text-right ml-auto',
        default  => 'text-left',
    };
@endphp

<section class="py-16">
    <div class="mx-auto max-w-4xl px-6 lg:px-8 {{ $alignClass }}">
        @if(!empty($c['title']))
            <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl mb-6">
                {{ $c['title'] }}
            </h2>
        @endif
        @if(!empty($c['body']))
            <div class="prose prose-lg max-w-none {{ $alignment === 'center' ? 'mx-auto' : '' }} text-slate-600">
                {!! $c['body'] !!}
            </div>
        @endif
    </div>
</section>
