@props(['as' => 'div'])

<{{ $as }} {{ $attributes->merge(['class' => 'glass-card rounded-3xl p-6']) }}>
    {{ $slot }}
</{{ $as }}>
