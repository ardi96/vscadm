<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home_new');
});

Route::get('/schedule', function () {
    return view('schedule');
});


Route::get('/download/raport/{record}' , DownloadController::class);