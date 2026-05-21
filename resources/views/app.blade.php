<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Lavender Grocery & Mart') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ config('app.name', 'Lavender Grocery & Mart') }}">
    <meta property="og:description" content="Fresh groceries delivered to your doorstep. Shop fresh fruits, vegetables, dairy, snacks, beverages, and household essentials with fast delivery.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="description" content="Lavender Grocery & Mart — Fresh groceries, fast delivery. Shop fresh fruits, vegetables, dairy, meat, snacks, and household essentials online.">

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-body antialiased bg-white text-charcoal">
    @inertia
</body>
</html>
