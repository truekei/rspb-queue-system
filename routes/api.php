<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DisplayCounterController;

Route::get('/staff', [StaffController::class, 'index']);

Route::get('/queue', [QueueController::class, 'index']);
Route::post('/queue', [QueueController::class, 'store']);
Route::post('/queue/{queueNumber}/called', [QueueController::class, 'calledUpdateQueue']);
Route::post('/queue/{queueNumber}/done', [QueueController::class, 'doneUpdateQueue']);

Route::post('/log', [LogController::class, 'store']);
Route::post('/log/{queueNumber}', [LogController::class, 'update']);

Route::post('/display-data', DisplayCounterController::class);
