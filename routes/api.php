<?php

use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Route;

Route::post('/object', [ObjectController::class, 'store']);
Route::get('/object/get_all_records', [ObjectController::class, 'index']);
Route::get('/object/get_all_latest_records', [ObjectController::class, 'latest']);
Route::get('/object/{key}', [ObjectController::class, 'show']);

