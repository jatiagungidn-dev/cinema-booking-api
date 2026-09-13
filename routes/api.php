<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ShowtimeContorller;
use App\Http\Controllers\Api\StudioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::get('/cinemas', [CinemaController::class, 'index']);
Route::get('/cinemas/{cinema}', [CinemaController::class, 'show']);
Route::get('/cinemas/{cinema}/studios', [CinemaController::class, 'studios']);

Route::get('/studios/{studio}/seats', [StudioController::class, 'seats']);

Route::get('/showtimes', [ShowtimeContorller::class, 'index']);
Route::get('/showtimes/{showtime}', [ShowtimeContorller::class, 'show']);

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/bookings/{booking}', [BookingController::class, 'show']);
Route::post('/bookings', [BookingController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
