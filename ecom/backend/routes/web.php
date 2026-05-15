<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => Log::channel('queue')->info('its working'));

require __DIR__ . '/admin.php';
