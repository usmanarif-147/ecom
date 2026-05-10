<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'test']);

require __DIR__ . '/admin.php';