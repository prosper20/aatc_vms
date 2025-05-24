<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AATC VMS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* General page styles */
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-color: #f9f9f9;
        }

        /* Main content padding */
        .main-content {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        /* Language switcher CSS */
        .language-switcher-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="language-switcher-container">
            @include('partials.language_switcher')
        </div>

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
