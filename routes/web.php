<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingDashboardController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\UserNotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BookingDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/bookings', [BookingDashboardController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/edit', [BookingDashboardController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingDashboardController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingDashboardController::class, 'destroy'])->name('bookings.destroy');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Admin Panel Routes (only accessible to admin users)
    Route::middleware('can:viewAny,App\Models\Booking')->group(function () {
        Route::get('/admin/bookings', [BookingAdminController::class, 'index'])->name('admin.bookings.index');
        Route::delete('/admin/bookings/{booking}', [BookingAdminController::class, 'destroy'])->name('admin.bookings.destroy');

    Route::middleware('auth')->group(function () {
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

        });
    });
});

require __DIR__.'/auth.php';
