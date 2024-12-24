<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @vite('resources/css/app.css')

    {{-- Map Box --}}
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />

    @livewireStyles
</head>

<body class="h-screen bg-gray-100">
    <main>
        @yield('content')
        {{ isset($slot) ? $slot : null }}
        {{-- @livewire('map-location') --}}
    </main>

    @livewireScripts
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    @stack('scripts')
</body>

</html>
