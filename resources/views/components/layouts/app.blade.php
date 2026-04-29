@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'An international network of community-driven colivings built on shared values, clear standards, and authentic human connection.' }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Gasoek+One&family=Montserrat:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Gasoek+One&family=Montserrat:wght@400;500;600;700;800&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>
<body class="min-h-screen flex flex-col">
    <x-site-header />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
