<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/public')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
});
