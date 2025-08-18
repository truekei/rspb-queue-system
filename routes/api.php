<?php

use App\Http\Controllers\BroadcastController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisplayCounterController;

Route::get('/staff', [StaffController::class, 'index']);

Route::get('/queue', [QueueController::class, 'index']);
Route::post('/queue', [QueueController::class, 'store']);
Route::post('/queue/{queueNumber}/called', [QueueController::class, 'calledUpdateQueue']);
Route::post('/queue/{queueNumber}/done', [QueueController::class, 'doneUpdateQueue']);

Route::post('/log', [LogController::class, 'store']);
Route::post('/log/{queueNumber}', [LogController::class, 'update']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::post('/display-data', [BroadcastController::class, 'callQueue']);
Route::post('/display-load', [BroadcastController::class, 'displayLoad']);
Route::post('/display-load/counters', [BroadcastController::class, 'displayLoadCounters']);
