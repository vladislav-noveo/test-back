<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AvailabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('token', [AuthController::class, 'store'])->name('token.get');

Route::get('doctors', [DoctorController::class, 'getList'])->name('doctors.get');

Route::get('doctors/{doctor}/availabilities', [AvailabilityController::class, 'getForDoctor'])->name('availabilities.get');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('bookings', [BookingController::class, 'getForUser'])->name('bookings.get');

    Route::post('bookings', [BookingController::class, 'create'])->name('bookings.create');

    Route::get('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});
