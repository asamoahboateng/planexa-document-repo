<?php

use Illuminate\Support\Facades\Route;

Route::group([ 'prefix' => 'backend', 'middleware' => 'web'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('backend.dashboard');
        Route::get('/meetings', \App\Livewire\Admin\General\ListMeetings::class)->name('meetings');
        Route::get('/locations', \App\Livewire\Admin\General\ListLocations::class)->name('locations');
        Route::get('/meeting-videos', \App\Livewire\Admin\General\ListMeetingVideos::class)->name('meeting-videos');
        Route::get('/applications', \App\Livewire\Admin\General\ListApplication::class)->name('applications');
//        Route::get('/', function () {
//            return view('backend.dashboard');
//        })->name('backend.dashboard');
    });

    Route::get('/admin/login', \App\Livewire\Admin\AuthorizeUsers::class )->name('login');
    Route::get('/users', \App\Livewire\Admin\ListUsers::class)->name('users');
});
//Route::get('/', function () {
//    return view('welcome');
//});
// users


