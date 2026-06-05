<x-filament-panels::page>
    <div class="space-y-8">

        {{-- Tour Detail --}}
        <x-filament::section>
            <x-slot name="heading">🗺 Tour Package Detail Page</x-slot>
            <x-slot name="description">Control the layout, header style, card appearance, and visible sections on every tour detail page.</x-slot>

            {{ $this->tourForm }}

            <div class="mt-6 flex justify-end">
                <x-filament::button wire:click="saveTour" icon="heroicon-o-check">
                    Save Tour Settings
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- Blog Detail --}}
        <x-filament::section>
            <x-slot name="heading">📰 Blog Post Detail Page</x-slot>
            <x-slot name="description">Control the layout, header style, card appearance, and visible sections on every blog post page.</x-slot>

            {{ $this->blogForm }}

            <div class="mt-6 flex justify-end">
                <x-filament::button wire:click="saveBlog" icon="heroicon-o-check">
                    Save Blog Settings
                </x-filament::button>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
