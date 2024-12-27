<?php

use App\Livewire\MapLocation;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', MapLocation::class);


