@props([
    'title' => null,
    'description' => null,
])

@php
    $generalSettings = $generalSettings ?? app(\App\Settings\GeneralSettings::class);
    $themeSettings = $themeSettings ?? app(\App\Settings\ThemeSettings::class);
    $seoSettings = $seoSettings ?? app(\App\Settings\SeoSettings::class);
@endphp

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $currentDirection ?? 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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

        {{-- Booking modal (global — triggered by Alpine open-booking-modal event) --}}
        <x-booking-modal />

        {{-- AI Chatbot Widget --}}
        <x-chatbot-widget />
    </body>
</html>
