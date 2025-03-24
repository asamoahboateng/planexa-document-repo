<?php

use Illuminate\Support\Facades\Route;

Route::prefix('backend')->group(function () {
    Route::get('/', function () {
        return view('backend.dashboard');
    })->name('backend');
});
//Route::get('/', function () {
//    return view('welcome');
//});
