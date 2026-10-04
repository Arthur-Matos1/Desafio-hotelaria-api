<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReserveController;

Route::apiResource('rooms', RoomController::class);
Route::post('/reserves', [ReserveController::class, 'store']);