<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\BookingController;

use App\Http\Middleware\AdminMiddleware;

// --- Public Routes (Tidak memerlukan Autentikasi) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Melihat daftar paket (semua bisa lihat)
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{package}', [PackageController::class, 'show']);

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{package}', [PackageController::class, 'update']);
    Route::delete('/packages/{package}', [PackageController::class, 'destroy']);

    Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus']); // Update status booking oleh admin
});

// --- Protected Routes (Membutuhkan Autentikasi Laravel Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // Mendapatkan info user yang login

    // --- Booking Routes (User & Admin Access) ---
    Route::get('/bookings', [BookingController::class, 'index']); // User melihat bookingnya sendiri
    Route::post('/bookings', [BookingController::class, 'store']); // User membuat booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show']); // User melihat detail bookingnya

});