<?php

use App\Livewire\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', Admin\Login::class)->name('login');
    Route::view('/dashboard', 'admin/dashboard')->name('dashboard');
    Route::view('/orders', 'admin.orders.index')->name('orders.index');
    Route::view('products', 'admin.products.index')->name('products.index');
    Route::view('products/create', 'admin.products.create')->name('products.create');
    Route::view('products/{id}/edit', 'admin.products.edit')->name('products.edit');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::get('/products', Admin\ProductIndex::class)->name('products.index');
    // Route::get('/products/create', Admin\ProductForm::class)->name('products.create');
    // Route::get('/products/{id}/edit', Admin\ProductForm::class)->name('products.edit');
    // Route::get('/orders', Admin\OrderIndex::class)->name('orders.index');
    // Route::post('/logout', fn () => redirect()->route('admin.dashboard'))->name('logout');

    // Route::get('/login', Admin\Login::class)->name('login');
    // Route::get('/dashboard', [Admin\Dashboard::class])->name('dashboard');
    // Route::get('/products', Admin\ProductIndex::class)->name('products.index');
    // Route::get('/products/create', Admin\ProductForm::class)->name('products.create');
    // Route::get('/products/{id}/edit', Admin\ProductForm::class)->name('products.edit');
    // Route::get('/orders', Admin\OrderIndex::class)->name('orders.index');
    Route::post('/logout', fn () => redirect()->route('admin.dashboard'))->name('logout');
});
