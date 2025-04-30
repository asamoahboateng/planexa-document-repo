<?php

use Illuminate\Support\Facades\Route;

Route::get('ui', function () {
    return view('website-new.home');
})->name('uikit');

Route::get('/home', function () {

//    return view('website.new-home');
    return view('website.ui');

//    return view('website.home');
})->name('home');
Route::redirect('/', 'home');

//Route::redirect('/', 'backend');
//Route::get('/', function () {
//    return view('welcome');
//})->middleware('auth');
Route::get('/map-two', function () {
    // Example coordinates data
    $coordinates = [
        'lat' => 43.7812974,
        'lng' => -79.4158993,
        'name' => 'Sample Location',
        'address' => '123 Example Street, City, Country',
        'type' => 'Building'
    ];

    return view('map-four', compact('coordinates'));
});
Route::get('/map', function () {
    return view('map-two');
});

Route::get('/search-locations', [\App\Http\Controllers\Website\LocationController::class, 'search'])->name('location-search');
Route::get('/search-locations-test', [\App\Http\Controllers\Website\LocationController::class, 'searchtest'])->name('location-search-test');
Route::get('/location/{id}', [\App\Http\Controllers\Website\LocationController::class, 'show'])->name('location-show');
Route::get('/location-application/{location_id}/{application_id}', [\App\Http\Controllers\Website\LocationController::class, 'application'])->name('location.application');
