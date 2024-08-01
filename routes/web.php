
<?php

use App\Http\Controllers\StoreWebController;
use Illuminate\Support\Facades\Route;

Route::get('/stores/create', [StoreWebController::class, 'create'])->name('stores.create');
Route::post('/stores', [StoreWebController::class, 'store'])->name('stores.store');
