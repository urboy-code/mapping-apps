<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @vite('resources/css/app.css')

    {{-- Map Box --}}
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />


</head>

<body class="h-screen bg-gray-100">
    <div id='map' class="h-screen absolute inset-0"></div>

    <div class="absolute top-5 left-5 bg-white p-4 rounded-lg shadow-lg w-80 z-10">
        <form action="" id="add-record" class="space-y-4">
            <h1 class="text-xl font-bold text-center ">Form</h1>
        </form>
    </div>

    @push('scripts')
        <script>
            const defaultLocation = [110.4088018890273, -6.9808335846684315];

            mapboxgl.accessToken =
                '{{ env('MAP_BOX_TOKEN') }}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 11,
                style: 'mapbox://styles/mapbox/streets-v11'
            });

            map.addControl(new mapboxgl.NavigationControl());

            map.on('click', (e) => {
                const longitude = e.lngLat.lng;
                const latitude = e.lngLat.lat;

                console.log({
                    longitude,
                    latitude
                });
            })
        </script>
    @endpush






    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    @stack('scripts')
</body>

</html>
