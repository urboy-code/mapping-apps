<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Record;
use Livewire\Component;
use Livewire\WithFileUploads;


class MapLocation extends Component
{
    use WithFileUploads;

    public $categories = [];
    public $category_id;
    public $long, $lat, $title, $description, $address, $category, $icon;
    public $geoJson;

    public function mount()
    {
        $this->categories = Category::all();
    }
    private function loadLocations()
    {
        $locations = Record::orderBy('created_at', 'desc')->get();
        $customLocation = [];

        foreach ($locations as $location) {
            $categoryName = $location->category->name;
            $iconUrl = match ($categoryName) {
                'Kuliner' => asset('icons/kuliner.png'),
                'Pariwisata' => asset('icons/pariwisata.png'),
                'Pendidikan' => asset('icons/pendidikan.png'),
                default => asset('icons/pariwisata.png'),
            };
            // dd($categoryName);

            $customLocation[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [
                        $location->longitude,
                        $location->latitude,
                    ],
                    'type' => 'Point'
                ],
                'properties' => [
                    'iconSize' => [
                        50,
                        50
                    ],
                    'locationId' => $location->id,
                    'title' => $location->title,
                    'description' => $location->description,
                    'address' => $location->address,
                    'icon' => $iconUrl
                ]
            ];
        }

        $geloLocation = [
            'type' => 'FeatureCollection',
            'features' => $customLocation
        ];

        $geoJson = collect($geloLocation)->toJson();
        $this->geoJson = $geoJson;
    }

    private function clearForm()
    {
        $this->title = '';
        $this->address = '';
        $this->description = '';
        $this->category_id = '';
        $this->long = '';
        $this->lat = '';
    }

    public function saveLocation()
    {
        $this->validate([
            'title' => 'required',
            'address' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'long' => 'required',
            'lat' => 'required',
        ]);

        $category = Category::find($this->category_id);

        Record::create([
            'title' => $this->title,
            'address' => $this->address,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'longitude' => $this->long,
            'latitude' => $this->lat,
            'icon' => $category->icon
        ]);

        $this->clearForm();
        $this->loadLocations();
        $this->dispatch('locationAdded', $this->geoJson);
    }

    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-location');
    }
}
