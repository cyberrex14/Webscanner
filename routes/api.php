<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::post('/scan', [ScanController::class, 'store']);
Route::get('/scan/{id}', [ScanController::class, 'result']);
