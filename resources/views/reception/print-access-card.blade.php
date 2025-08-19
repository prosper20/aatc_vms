<!DOCTYPE html>
<html>
<head>
    <title>Print Card</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        secondary: '#f3f4f6',
                    }
                }
            }
        }
    </script>
    <style>
        .tab-content {
    transition: opacity 1s ease;
}
    </style>
</head>
<body>
    @if ($side === 'front')
        <div>
            @include('partials.access-card.front', ['card' => $card, 'visitor' => $visitor, 'staff' => $staff])
        </div>
    @else
        <div>
            @include('partials.access-card.back', ['card' => $card, 'visitor' => $visitor, 'staff' => $staff])
        </div>
    @endif
</body>
</html>

