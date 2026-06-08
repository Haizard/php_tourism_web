@props(['tabs' => []])

<!-- stylelint-disable -->
<div x-data="{ activeTab: 0 }" class="space-y-6">
    <!-- Tab Headers -->
    <div class="flex flex-wrap gap-3 border-b-2 border-slate-200">
        @foreach($tabs as $index => $tab)
            <button
                @click="activeTab = {{ $index }}"
                :class="{
                    'opacity-100 shadow-md': activeTab === {{ $index }},
                    'opacity-70 hover:opacity-85': activeTab !== {{ $index }}
                }"
                class="px-5 py-3 rounded-lg font-semibold text-white transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                style="background-color: {{ $tab['bgColor'] ?? 'var(--color-primary)' }}"
            >
                @if($tab['icon'] ?? false)
                    <span class="text-lg">{!! $tab['icon'] !!}</span>
                @endif
                <span class="text-sm font-bold">{{ $tab['label'] }}</span>
            </button>
        @endforeach
    </div>

    <!-- Tab Contents -->
    <div class="relative">
        @foreach($tabs as $index => $tab)
            <div
                x-show="activeTab === {{ $index }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="space-y-6"
            >
                {!! $tab['content'] !!}
            </div>
        @endforeach
    </div>
</div>
<!-- stylelint-enable -->
