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

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 font-sans bg-gray-100 font-nunito relative w-full">

    <div id="app">
        <!-- Language Switcher Positioned Top-Right -->
        {{-- <div class="absolute top-5 right-5 z-10">
            @include('partials.language_switcher')
        </div> --}}

        <!-- Main Content Area -->
        <main class="main-content w-full">
            @yield('content')
        </main>
    </div>

</body>
</html>
