<?php

use Illuminate\Support\Facades\Route;

Route::prefix('backend')->group(function () {
    Route::get('/', function () {
        return view('backend.dashboard');
    });
});
//Route::get('/', function () {
//    return view('welcome');
//});
// users
Route::get('/users', \App\Livewire\Admin\ListUsers::class)->name('users');
