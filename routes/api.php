<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\DisplayCounterController;

Route::get('/staff', [StaffController::class, 'index']);

Route::get('/queue', [QueueController::class, 'index']);
Route::post('/queue', [QueueController::class, 'store']);
Route::post('/queue/{queueNumber}/called', [QueueController::class, 'calledUpdateQueue']);
Route::post('/queue/{queueNumber}/done', [QueueController::class, 'doneUpdateQueue']);

Route::post('/display-data', DisplayCounterController::class);
