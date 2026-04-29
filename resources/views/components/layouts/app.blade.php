@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogImage' => null,
    'robots' => null,
    'schema' => null,
    'hideHeader' => false,
])

@php
    $resolvedTitle = $title ?? config('app.name');
    $resolvedDescription = $description ?? 'An international network of community-driven colivings built on shared values, clear standards, and authentic human connection.';
    $resolvedCanonical = $canonical ?? url(request()->path());
    $resolvedOgImage = $ogImage ?? url('/og/default.png');
    $resolvedRobots = $robots ?: 'index, follow';
    $organizationLd = app(\App\Services\SchemaBuilder::class)->organization()->toScript();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $resolvedTitle }}</title>
    <meta name="description" content="{{ $resolvedDescription }}">
    <meta name="robots" content="{{ $resolvedRobots }}">
    <link rel="canonical" href="{{ $resolvedCanonical }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $resolvedTitle }}">
    <meta property="og:description" content="{{ $resolvedDescription }}">
    <meta property="og:url" content="{{ $resolvedCanonical }}">
    <meta property="og:image" content="{{ $resolvedOgImage }}">
    <meta property="og:site_name" content="Coliving Founders">
    <meta property="og:locale" content="en">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $resolvedTitle }}">
    <meta name="twitter:description" content="{{ $resolvedDescription }}">
    <meta name="twitter:image" content="{{ $resolvedOgImage }}">

    <link rel="alternate" type="application/rss+xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Gasoek+One&family=Montserrat:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Gasoek+One&family=Montserrat:wght@400;500;600;700;800&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {!! $organizationLd !!}
    @if ($schema)
        {!! $schema !!}
    @endif

    {{ $head ?? '' }}
</head>
<body class="min-h-screen flex flex-col">
    @unless ($hideHeader)
        <x-site-header />
    @endunless

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
