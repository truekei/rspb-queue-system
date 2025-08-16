<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('DisplayPage');
})->name('home');

Route::get('/call', function () {
    return Inertia::render('CallPage');
})->name('call');

Route::get('/queue', function () {
    return Inertia::render('QueuePage');
})->name('queue');

Route::get('/dashboard', function () {
    return Inertia::render('DashboardPage');
})->name('dashboard');
