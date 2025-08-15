<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;

Route::get('/staff', [StaffController::class, 'index']);

Route::post('/queue', [QueueController::class, 'store']);
