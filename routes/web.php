<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PGController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home_new');
});

Route::get('/schedule', function () {
    return view('schedule');
});


Route::get('/download/raport/{record}' , DownloadController::class);

Route::get('/portal/payment/received', [PGController::class,'receiptPage']);