<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::post('/scan', [ScanController::class, 'start']);
