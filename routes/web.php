<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home_new');
});

Route::get('/schedule', function () {
    return view('schedule');
});


Route::post('/payment/success', [App\Http\Controllers\PGController::class, 'notifySuccess']);
Route::post('/payment/failure', [App\Http\Controllers\PGController::class, 'notifyFailure']);
Route::post('/payment/other', [App\Http\Controllers\PGController::class, 'notifyOther']);

Route::get('/download/raport/{record}' , DownloadController::class);