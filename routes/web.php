<?php

use Illuminate\Support\Facades\Route;


//Route::get('/', function () {
//    return view('welcome');
//});
//Route::redirect('/', 'backend');

//Route::redirect('/', 'backend');
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');
