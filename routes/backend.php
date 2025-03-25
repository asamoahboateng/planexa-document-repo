<?php

use Illuminate\Support\Facades\Route;

Route::group([ 'prefix' => 'backend', 'middleware' => 'web'], function () {
    Route::get('/', function () {
        return view('backend.layouts.auth');
    });
    Route::get('/admin/login', \App\Livewire\Admin\AuthorizeUsers::class )->name('admin.login');
    Route::get('/users', \App\Livewire\Admin\ListUsers::class)->name('users');
});
//Route::get('/', function () {
//    return view('welcome');
//});
// users


