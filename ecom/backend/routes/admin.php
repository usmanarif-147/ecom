<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::view('/login', 'admin.login')->name('login')->middleware(['is_admin:guest']);

    Route::middleware(['is_admin'])->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/categories', 'admin.categories.index')->name('categories.index');
        Route::view('/colors', 'admin.colors.index')->name('colors.index');
        Route::view('/sizes', 'admin.sizes.index')->name('sizes.index');
        Route::view('/orders', 'admin.orders.index')->name('orders.index');
        Route::view('/products', 'admin.products.index')->name('products.index');
        Route::view('/products/create', 'admin.products.create')->name('products.create');
        Route::view('/products/{id}/edit', 'admin.products.edit')->name('products.edit');
    });
});
