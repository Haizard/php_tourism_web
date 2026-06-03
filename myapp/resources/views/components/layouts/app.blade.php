@props([
    'title' => 'Tourism Starter Kit',
    'description' => 'A reusable Laravel tourism management starter kit.',
])

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $currentDirection ?? 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <x-seo :title="$title" :description="$description" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[var(--color-bg)] text-slate-950 antialiased">
        <div class="gradient-bg min-h-screen">
            @include('partials.header')

            <main>
                {{ $slot }}
            </main>

            @include('partials.footer')
        </div>

        {{-- Future AI chatbot integration point. --}}
    </body>
</html>
