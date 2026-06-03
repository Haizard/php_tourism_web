@props([
    'title' => 'Tourism Starter Kit',
    'description' => 'A reusable Laravel tourism management starter kit.',
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="website">
