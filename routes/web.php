<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DroneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
/*
| Web Routes
*/

/*
|-------------------------
| Public routes (no auth)
|-------------------------
*/

Route::get('/', [DroneController::class, 'index'])->name('home');
Route::get('/drones', [DroneController::class, 'index'])->name('drones.index');
Route::get('/drones/{drone}', [DroneController::class, 'show'])->name('drones.show');

/*
|-------------------------
| Authentication (public)
| - login & logout MUST be outside auth group
|-------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|-------------------------
| Protected user routes (auth)
|-------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Example: booking flows (enable if controllers & views ready)
    Route::get('/drones/{drone}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/drones/{drone}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');

    // Inspections on bookings
    Route::post('/bookings/{booking}/preflight', [InspectionController::class, 'preflight'])->name('inspections.preflight');
    Route::post('/bookings/{booking}/postflight', [InspectionController::class, 'postflight'])->name('inspections.postflight');

    // My bookings
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
});

/*
|-------------------------
| Admin area (auth + admin)
| - admin middleware must be registered in Kernel (see app/Http/Kernel.php)
| - AdminMiddleware should abort(403) for non-admins (do NOT redirect to /login)
|-------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // ...
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/drones', [AdminController::class, 'dronesIndex'])->name('admin.drones.index');
    Route::get('/drones/create', [AdminController::class, 'dronesCreate'])->name('admin.drones.create');
    Route::post('/drones', [AdminController::class, 'dronesStore'])->name('admin.drones.store');
    Route::get('/drones/{drone}/edit', [AdminController::class, 'dronesEdit'])->name('admin.drones.edit');
    Route::put('/drones/{drone}', [AdminController::class, 'dronesUpdate'])->name('admin.drones.update');
    Route::delete('/drones/{drone}', [AdminController::class, 'dronesDestroy'])->name('admin.drones.destroy');

    Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('admin.bookings.index');
    Route::get('/bookings/print', [AdminController::class, 'bookingsPrint'])->name('admin.bookings.print');

    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('admin.payments.refund');
    // manage units
    Route::get('/drones/{drone}/units/create', [AdminController::class, 'unitsCreate'])->name('admin.drones.units.create');
    Route::post('/drones/{drone}/units', [AdminController::class, 'unitsStore'])->name('admin.drones.units.store');
    Route::delete('/units/{unit}', [AdminController::class, 'unitsDestroy'])->name('admin.units.destroy');
    // di dalam group admin (pastikan admin middleware ada)
    Route::post('/bookings/{booking}/return', [AdminController::class, 'returnBooking'])
        ->name('admin.bookings.return')
        ->middleware('admin');
    Route::get('admin/bookings/{booking}', [AdminController::class, 'bookingShow'])->name('admin.bookings.show')->middleware(['auth', 'admin']);
    Route::post('admin/bookings/{booking}/return', [AdminController::class, 'returnBooking'])->name('admin.bookings.return')->middleware(['auth', 'admin']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->names('admin.categories');
});
    // {
    //     $bookingConfig = Booking::getBookingConfig();

    //     // hitung keterlambatan
    //     $now = Carbon::now();
    //     $lateDays = 0;
    //     $finePerDay = 0;
    //     $lateFee = 0;

    //     if ($now->greaterThan($booking->end_at)) {
    //         $lateDays = (int) ceil($now->diffInSeconds($booking->end_at) / 86400);
    //         if ($lateDays > $bookingConfig['max_days_without_fine']) {
    //             $chargeableLateDays = $lateDays - $bookingConfig['max_days_without_fine'];
    //             $dailyRate = $booking->drone->hourly_rate * 24;
    //             $finePerDay = $dailyRate * $bookingConfig['fine_percent_of_daily_rate'];
    //             $lateFee = $finePerDay * $chargeableLateDays;
    //         }
    //     }

    //     // update booking dengan info keterlambatan
    //     $booking->late_days = $lateDays;
    //     $booking->fine_per_day = $finePerDay;
    //     $booking->late_fee = $lateFee;
    //     $booking->status = 'returned';
    //     $booking->processed_by = auth()->id();
    //     $booking->save();

    //     // update status unit drone jika ada
    //      if ($booking->drone_unit_id) {
    //         $unit = $booking->droneUnit;
    //         if ($unit) {
    //              $unit->status = 'available';
    //              $unit->save();
    //          }
    //      }

    //     return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil diproses pengembaliannya.');
    // }




// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Public routes, auth routes, and admin routes (protected by auth + admin).
// |
// */

// /*
// |-------------------------
// | Public routes (no auth)
// |-------------------------
// */
// Route::get('/', [DroneController::class, 'index'])->name('home');
// Route::get('/drones', [DroneController::class, 'index'])->name('drones.index');
// Route::get('/drones/{drone}', [DroneController::class, 'show'])->name('drones.show');

// /*
// |-------------------------
// | Authentication (public)
// | - login & logout must be outside auth group
// |-------------------------
// */
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// /*
// |-------------------------
// | Protected user routes (auth)
// |-------------------------
// */
// Route::middleware(['auth'])->group(function () {
//     // Booking flows for normal users
//     Route::get('/drones/{drone}/book', [BookingController::class, 'create'])->name('bookings.create');
//     Route::post('/drones/{drone}/book', [BookingController::class, 'store'])->name('bookings.store');
//     Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');

//     // Inspections on bookings
//     Route::post('/bookings/{booking}/preflight', [InspectionController::class, 'preflight'])->name('inspections.preflight');
//     Route::post('/bookings/{booking}/postflight', [InspectionController::class, 'postflight'])->name('inspections.postflight');

//     // My bookings (user hanya melihat booking miliknya)
//     Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');

//     // Profile
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
// });

// /*
// |-------------------------
// | Admin area (auth + admin)
// | - pastikan 'admin' middleware didaftarkan di app/Http/Kernel.php
// |-------------------------
// */
// Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
//     // Dashboard
//     Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

//     // Drone CRUD (admin)
//     Route::get('/drones', [AdminController::class, 'dronesIndex'])->name('admin.drones.index');
//     Route::get('/drones/create', [AdminController::class, 'dronesCreate'])->name('admin.drones.create');
//     Route::post('/drones', [AdminController::class, 'dronesStore'])->name('admin.drones.store');
//     Route::get('/drones/{drone}/edit', [AdminController::class, 'dronesEdit'])->name('admin.drones.edit');
//     Route::put('/drones/{drone}', [AdminController::class, 'dronesUpdate'])->name('admin.drones.update');
//     Route::delete('/drones/{drone}', [AdminController::class, 'dronesDestroy'])->name('admin.drones.destroy');

//     // Drone units (unit-level operations)
//     Route::get('/drones/{drone}/units/create', [AdminController::class, 'unitsCreate'])->name('admin.drones.units.create');
//     Route::post('/drones/{drone}/units', [AdminController::class, 'unitsStore'])->name('admin.drones.units.store');
//     Route::delete('/units/{unit}', [AdminController::class, 'unitsDestroy'])->name('admin.units.destroy');

//     // Bookings management + printing
//     Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('admin.bookings.index');
//     Route::get('/bookings/print', [AdminController::class, 'bookingsPrint'])->name('admin.bookings.print');
//     Route::get('/bookings/{booking}', [AdminController::class, 'bookingShow'])->name('admin.bookings.show');
//     Route::post('/bookings/{booking}/return', [AdminController::class, 'returnBooking'])->name('admin.bookings.return');

//     // Payments
//     Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
//     Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('admin.payments.refund');

//     // Categories resource (admin)
//     Route::resource('categories', CategoryController::class, ['as' => 'admin']);
// }); -->
