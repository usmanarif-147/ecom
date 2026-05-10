<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::view('/login', 'admin.login')->name('login')->middleware(['is_admin:guest']);

    Route::middleware(['is_admin'])->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/orders', 'admin.orders.index')->name('orders.index');
        Route::view('/products', 'admin.products.index')->name('products.index');
        Route::view('/products/create', 'admin.products.create')->name('products.create');
        Route::view('/products/{id}/edit', 'admin.products.edit')->name('products.edit');
        Route::post('/logout', fn() => redirect()->route('admin.dashboard'))->name('logout');
    });
});
