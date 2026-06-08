@props(['items' => []])

<!-- stylelint-disable -->
<div class="space-y-3">
    @foreach($items as $index => $item)
        <details class="group rounded-lg border border-slate-200 bg-white/50 hover:bg-white/70 transition-colors">
            <summary 
                class="cursor-pointer select-none px-6 py-4 flex items-center justify-between font-semibold text-white hover:opacity-90 transition-opacity rounded-t-lg"
                style="background-color: {{ $item['bgColor'] ?? 'var(--color-primary)' }}"
            >
                <span class="flex items-center gap-3">
                    @if($item['icon'] ?? false)
                        <span class="text-lg">{!! $item['icon'] !!}</span>
                    @endif
                    {{ $item['title'] }}
                </span>
                <svg class="h-5 w-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </summary>
            <div class="border-t border-slate-200 px-6 py-4 text-slate-600">
                @if(is_array($item['content']))
                    <ul class="space-y-3">
                        @foreach($item['content'] as $subKey => $subValue)
                            <li class="flex items-start gap-3">
                                @if($item['listIcon'] ?? false)
                                    <span class="text-lg flex-shrink-0 mt-0.5">{!! $item['listIcon'] !!}</span>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $subKey }}</p>
                                    @if($subValue)
                                        <p class="text-sm text-slate-600 mt-1">{{ $subValue }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{ $item['content'] }}
                @endif
            </div>
        </details>
    @endforeach
</div>
<!-- stylelint-enable -->
