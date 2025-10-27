<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DroneController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/drones', [DroneController::class, 'index'])->name('drones.index');
    Route::get('/drones/search', [DroneController::class, 'search'])->name('drones.search');
    Route::get('/drones/{drone}', [DroneController::class, 'show'])->name('drones.show');
    
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
});

// Admin routes
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::resource('admin/users', AdminController::class);
    Route::resource('admin/drones', DroneController::class);
    Route::resource('admin/categories', CategoryController::class);
    Route::get('/admin/rentals', [RentalController::class, 'adminIndex'])->name('admin.rentals.index');
    Route::get('/admin/rentals/{rental}/return', [RentalController::class, 'returnRental'])->name('admin.rentals.return');
});