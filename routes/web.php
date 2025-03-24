<?php

use Illuminate\Support\Facades\Route;

//Route::redirect('/', 'backend');
Route::get('/', function () {
    return view('welcome');
});
