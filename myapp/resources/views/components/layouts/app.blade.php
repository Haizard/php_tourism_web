@props([
    'title' => null,
    'description' => null,
])

@php
    $generalSettings = $generalSettings ?? app(\App\Settings\GeneralSettings::class);
    $themeSettings = $themeSettings ?? app(\App\Settings\ThemeSettings::class);
    $seoSettings = $seoSettings ?? app(\App\Settings\SeoSettings::class);
    $locale = $currentLocale ?? app()->getLocale();
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $currentDirection ?? 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if ($generalSettings->favicon)
            <link rel="icon" href="{{ $generalSettings->favicon }}">
        @endif
        <style>
            :root {
                --font-family: {{ $themeSettings->fontFamily }};
                --color-primary: {{ $themeSettings->primaryColor }};
                --color-accent: {{ $themeSettings->accentColor }};
                --color-bg: {{ $themeSettings->backgroundColor }};
                --hero-overlay-opacity: {{ $themeSettings->heroOverlayOpacity }};
            }
        </style>
        @if (!empty($themeSettings->customCss))
        <style id="custom-css">
            {{ $themeSettings->customCss }}
        </style>
        @endif
        <x-seo :title="$title ?? $generalSettings->siteName" :description="$description ?? $generalSettings->tagline" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[var(--color-bg)] text-slate-950 antialiased" style="font-family: var(--font-family);">
        <div class="gradient-bg min-h-screen">
            @include('partials.header')

            <main>
                {{ $slot }}
            </main>

            @include('partials.footer')
        </div>

        {{-- Booking modal (global) --}}
        <x-booking-modal />

        {{-- AI Chatbot Widget --}}
        <x-chatbot-widget />

        {{-- Back to top button --}}
        <button
            id="back-to-top"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-24 right-6 z-[9997] hidden h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/90 shadow-lg backdrop-blur-sm text-slate-600 transition hover:bg-white hover:text-[var(--color-primary)] hover:shadow-xl hover:-translate-y-0.5 active:scale-95"
            aria-label="Back to top"
            title="Back to top">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        {{-- WhatsApp / phone quick-contact (bottom-left) --}}
        @php $phone = preg_replace('/[^+\d]/', '', $generalSettings->contactPhone ?? ''); @endphp
        @if ($phone)
        <a href="https://wa.me/{{ ltrim($phone, '+') }}"
           target="_blank" rel="noopener"
           title="Chat on WhatsApp"
           class="fixed bottom-6 left-6 z-[9997] flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg transition hover:bg-green-600 hover:scale-105 hover:shadow-xl active:scale-95"
           aria-label="WhatsApp">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
        @endif

        <script>
            // Show / hide back-to-top button
            (function () {
                const btn = document.getElementById('back-to-top');
                if (!btn) return;
                const toggle = () => {
                    if (window.scrollY > 400) {
                        btn.classList.remove('hidden');
                        btn.classList.add('flex');
                    } else {
                        btn.classList.add('hidden');
                        btn.classList.remove('flex');
                    }
                };
                window.addEventListener('scroll', toggle, { passive: true });
                toggle();
            })();
        </script>
    </body>
</html>
