<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'test']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', Admin\Dashboard::class)->name('dashboard');
    Route::get('/products', Admin\ProductIndex::class)->name('products.index');
    Route::get('/products/create', Admin\ProductForm::class)->name('products.create');
    Route::get('/products/{id}/edit', Admin\ProductForm::class)->name('products.edit');
    Route::get('/orders', Admin\OrderIndex::class)->name('orders.index');
    Route::post('/logout', fn () => redirect()->route('admin.dashboard'))->name('logout');
});
