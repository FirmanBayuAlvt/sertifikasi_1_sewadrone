<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DroneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;

/*
| Web Routes
*/

Route::get('/', [DroneController::class,'index'])->name('home');
Route::get('/drones', [DroneController::class,'index'])->name('drones.index');
Route::get('/drones/{drone}', [DroneController::class,'show'])->name('drones.show');

// Booking & Inspections (require auth)
Route::middleware(['auth'])->group(function () {
    // Route::get('/drones/{drone}/book', [BookingController::class,'create'])->name('bookings.create');
    // Route::post('/drones/{drone}/book', [BookingController::class,'store'])->name('bookings.store');
    // Route::get('/bookings/{booking}/success', [BookingController::class,'success'])->name('bookings.success');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/bookings/{booking}/preflight', [InspectionController::class,'preflight'])->name('inspections.preflight');
    Route::post('/bookings/{booking}/postflight', [InspectionController::class,'postflight'])->name('inspections.postflight');

    // Route::get('/my-bookings', [BookingController::class,'myBookings'])->name('bookings.my');
});

// Admin area (recommended protect with admin middleware)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class,'dashboard'])->name('admin.dashboard');

    Route::get('/drones', [AdminController::class,'dronesIndex'])->name('admin.drones.index');
    Route::get('/drones/create', [AdminController::class,'dronesCreate'])->name('admin.drones.create');
    Route::post('/drones', [AdminController::class,'dronesStore'])->name('admin.drones.store');
    Route::get('/drones/{drone}/edit', [AdminController::class,'dronesEdit'])->name('admin.drones.edit');
    Route::put('/drones/{drone}', [AdminController::class,'dronesUpdate'])->name('admin.drones.update');
    Route::delete('/drones/{drone}', [AdminController::class,'dronesDestroy'])->name('admin.drones.destroy');

    Route::get('/bookings', [AdminController::class,'bookingsIndex'])->name('admin.bookings.index');

    Route::get('/payments', [PaymentController::class,'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/refund', [PaymentController::class,'refund'])->name('admin.payments.refund');
});
