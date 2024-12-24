<div>
    <div wire:ignore id='map' class="h-screen w-full absolute inset-0"></div>

    <div class="absolute top-5 left-5 bg-white p-4 rounded-lg shadow-lg w-80 z-10">
        <form wire:submit.prevent="saveLocation" id="add-record" class="space-y-4">
            <h1 class="text-xl font-bold text-center ">Form</h1>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="first-name" class="block text-sm/6 font-medium text-gray-900">Longitude</label>
                    <div class="mt-2">
                        <input wire:model="long" type="text" name="first-name" id="first-name"
                            autocomplete="given-name"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                        @error('long')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Latitude</label>
                    <div class="mt-2">
                        <input wire:model="lat" type="text" name="last-name" id="last-name"
                            autocomplete="family-name"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                        @error('lat')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="title" class="block text-sm/6 font-medium text-gray-900">Title</label>
                    <div class="mt-2">
                        <input wire:model="title" id="title" name="title" type="text" autocomplete="title"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                        @error('title')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="address" class="block text-sm/6 font-medium text-gray-900">Address</label>
                    <div class="mt-2">
                        <input wire:model="address" id="address" name="address" type="text" autocomplete="address"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                        @error('address')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="category" class="block text-sm/6 font-medium text-gray-900">Category</label>
                    <select wire:model="category_id" id="category" name="category" aria-label="category"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                        <option>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea wire:model="description" name="description" id="description" rows="3"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                        @error('description')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-28" type="submit">
                    Button
                </button>
        </form>
    </div>
</div>



@push('scripts')
    <script>
        const defaultLocation = [110.4088018890273, -6.9808335846684315];
        // defaultLocation = [107.62059, -6.90389];

        mapboxgl.accessToken =
            '{{ env('MAP_BOX_TOKEN') }}';
        const map = new mapboxgl.Map({
            container: "map",
            center: defaultLocation,
            zoom: 11,
            style: 'mapbox://styles/mapbox/streets-v11'
        });

        const loadLocations = (geoJson) => {
            geoJson.features.forEach((location) => {
                const {
                    geometry,
                    properties
                } = location
                const {
                    iconSize,
                    locationId,
                    title,
                    image,
                    description,
                    address,
                    icon,
                    category
                } = properties

                // console.log(location.properties.icon);

                let markerElement = document.createElement('div');
                markerElement.className = 'marker' + locationId;
                markerElement.id = locationId;
                markerElement.style.backgroundImage =
                    `url(${icon})`;
                markerElement.style.backgroundSize = 'cover';
                markerElement.style.width = '20px';
                markerElement.style.height = '20px';

                const content = `
                    <div style="overflow-y; max-height:500px, width:100%">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Title</td>
                                    <td>${category}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>${address}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>${description}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;

                const popUp = new mapboxgl.Popup({
                    offset: 25
                }).setHTML(content).setMaxWidth("400px");

                new mapboxgl.Marker(markerElement)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popUp)
                    .addTo(map)
            })
        }

        loadLocations({!! $geoJson !!});

        window.addEventListener('locationAdded', (e) => {
            loadLocations(JSON.parse(e.detail));
        })

        map.addControl(new mapboxgl.NavigationControl());

        map.on('click', (e) => {
            const longitude = e.lngLat.lng;
            const latitude = e.lngLat.lat;

            @this.long = longitude;
            @this.lat = latitude;
        })
    </script>
@endpush
