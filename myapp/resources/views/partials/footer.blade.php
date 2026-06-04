@php($locale = $currentLocale ?? app()->getLocale())

<footer class="border-t border-white/20 bg-slate-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-12 md:grid-cols-3 lg:px-8">
        <div>
            <p class="text-lg font-black">{{ $generalSettings->siteName ?? 'Tourism Starter Kit' }}</p>
            <p class="mt-3 max-w-sm text-sm leading-6 text-slate-300">
                {{ $generalSettings->tagline ?? 'A Laravel foundation for multilingual tourism websites, booking flows, and content-managed travel experiences.' }}
            </p>
        </div>

        <div>
            <p class="font-bold">Contact</p>
            <div class="mt-3 space-y-2 text-sm text-slate-300">
                <a href="mailto:{{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}" class="block hover:text-white">
                    {{ $mailSettings->adminNotificationEmail ?? $generalSettings->contactEmail }}
                </a>
                <a href="tel:{{ $generalSettings->contactPhone }}" class="block hover:text-white">
                    {{ $generalSettings->contactPhone }}
                </a>
                <p>{{ $generalSettings->address }}</p>
            </div>
        </div>

        <div>
            <p class="font-bold">Explore</p>
            <div class="mt-3 grid gap-2 text-sm text-slate-300">
                <a href="{{ url("/{$locale}/tours") }}">Tours</a>
                <a href="{{ url("/{$locale}/destinations") }}">Destinations</a>
                <a href="{{ url("/{$locale}/gallery") }}">Gallery</a>
            </div>
        </div>

        @if (!empty($generalSettings->socialLinks))
            <div>
                <p class="font-bold">Follow Us</p>
                <div class="mt-3 flex flex-wrap gap-3 text-sm">
                    @foreach ($generalSettings->socialLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/10 px-3 py-2 text-slate-200 transition hover:bg-white/10">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="border-t border-white/10 px-6 py-4">
        <div class="mx-auto flex max-w-7xl items-center justify-between text-xs text-slate-500 lg:px-8">
            <span>&copy; {{ date('Y') }} {{ $generalSettings->siteName ?? 'Tourism Starter Kit' }}</span>
            <a href="{{ url('/admin') }}" class="flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1.5 text-slate-400 transition hover:border-white/25 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                </svg>
                Admin Login
            </a>
        </div>
    </div>
</footer>
