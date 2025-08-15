<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;


Route::post('/queue', [QueueController::class, 'store']);
