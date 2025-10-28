<?php

use Illuminate\Support\Facades\Route;


Route::post('/payment/success', [App\Http\Controllers\PGController::class, 'notifySuccess']);
Route::post('/payment/failure', [App\Http\Controllers\PGController::class, 'notifyFailure']);
Route::post('/payment/other', [App\Http\Controllers\PGController::class, 'notifyOther']);
