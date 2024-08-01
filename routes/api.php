<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('/stores', [StoreController::class, 'store']);
Route::get('/stores/near', [StoreController::class, 'getStoresNearPostcode']);
Route::get('/stores/delivering', [StoreController::class, 'getStoresDeliveringToPostcode']);
