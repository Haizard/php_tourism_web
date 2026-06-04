@props([
    'title' => null,
    'description' => null,
])

@php
    $seoSettings = $seoSettings ?? app(\App\Settings\SeoSettings::class);
    $generalSettings = $generalSettings ?? app(\App\Settings\GeneralSettings::class);
    $title = $title ?? $seoSettings->defaultMetaTitle ?? $generalSettings->siteName;
    $description = $description ?? $seoSettings->defaultMetaDescription ?? $generalSettings->tagline;
    $twitterHandle = $seoSettings->twitterHandle ? trim($seoSettings->twitterHandle) : null;
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="website">
@if ($seoSettings->ogImage)
    <meta property="og:image" content="{{ $seoSettings->ogImage }}">
    <meta name="twitter:image" content="{{ $seoSettings->ogImage }}">
@endif
@if ($twitterHandle)
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ $twitterHandle }}">
    <meta name="twitter:creator" content="{{ $twitterHandle }}">
@endif
