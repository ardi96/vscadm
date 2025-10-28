<?php

use Illuminate\Support\Facades\Route;


Route::post('/api/payment/success', [App\Http\Controllers\PGController::class, 'notifySuccess']);
Route::post('/api/payment/failure', [App\Http\Controllers\PGController::class, 'notifyFailure']);
Route::post('/api/payment/other', [App\Http\Controllers\PGController::class, 'notifyOther']);
