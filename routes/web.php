<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/call', function () {
    return Inertia::render('CallPage');
})->name('call');

Route::get('/queue', function () {
    return Inertia::render('QueuePage');
})->name('queue');
