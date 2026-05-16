<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $exception = DB::table('failed_jobs')->latest('failed_at')->first();
    dd($exception);
});

require __DIR__ . '/admin.php';
