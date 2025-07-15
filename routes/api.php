<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookingController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('api.bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('api.bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('api.bookings.destroy');
});
    