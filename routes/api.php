<?php

use Illuminate\Support\Facades\Route;


Route::post('/payment/notifications', [App\Http\Controllers\PGController::class, 'notifications']);
