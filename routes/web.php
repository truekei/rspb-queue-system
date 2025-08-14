<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QueueController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/queue', function () {
    return Inertia::render('QueuePage');
})->name('queue');

Route::prefix('api')->group(function () {
    Route::post('/queue', [QueueController::class, 'store']);
});
