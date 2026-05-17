<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SizeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/public')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/sizes', [SizeController::class, 'index']);
});
