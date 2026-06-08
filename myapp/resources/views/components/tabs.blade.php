@props(['tabs' => []])

<div x-data="{ activeTab: 0 }" class="space-y-6">
    <!-- Tab Headers -->
    <div class="flex flex-wrap gap-2 border-b-2 border-slate-200 overflow-x-auto">
        @foreach($tabs as $index => $tab)
            <button
                @click="activeTab = {{ $index }}"
                :class="{
                    'border-b-4 border-[var(--color-primary)] text-[var(--color-primary)] font-bold': activeTab === {{ $index }},
                    'border-b-4 border-transparent text-slate-600 hover:text-slate-900': activeTab !== {{ $index }}
                }"
                class="px-6 py-4 text-lg font-semibold transition-all duration-300 relative group"
            >
                <span class="flex items-center gap-2">
                    @if($tab['icon'] ?? false)
                        <span class="text-2xl">{!! $tab['icon'] !!}</span>
                    @endif
                    {{ $tab['label'] }}
                </span>
                <span :class="{ 'w-full': activeTab === {{ $index }}, 'w-0': activeTab !== {{ $index }} }" class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] rounded-full transition-all duration-300"></span>
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
