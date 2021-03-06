<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PassengerPagesController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Verify;
use App\Http\Livewire\Conductor\ConductorAnnouncement;
use App\Http\Livewire\Conductor\ConductorBookings;
use App\Http\Livewire\Conductor\ConductorDashboard;
use App\Http\Livewire\Conductor\ConductorDispatches;
use App\Http\Livewire\Conductor\ConductorScanQR;
use App\Http\Livewire\Conductor\ConductorViewBooking;
use App\Http\Livewire\Passenger\PassengerAnnouncement;
use App\Http\Livewire\Passenger\PassengerBookings;
use App\Http\Livewire\Passenger\PassengerDashboard;
use App\Http\Livewire\Passenger\PassengerDispatches;
use App\Http\Livewire\Passenger\PassengerFareMatrix;
use App\Http\Livewire\Passenger\PassengerViewBooking;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PagesController::class, 'validate_user'])->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('logout', LogoutController::class)
        ->name('logout');
});

Route::get('download-app', [PagesController::class, 'download_app'])->name('download_app');

Route::middleware(['auth', "role_id:" . Role::PASSENGER])
    ->name('passenger.')
    ->prefix('passenger')
    ->group(function () {
        Route::get('dashboard', PassengerDashboard::class)->name('dashboard');
        Route::get('fare_matrix', PassengerFareMatrix::class)->name('fare_matrix');
        Route::get('dispatches', PassengerDispatches::class)->name('dispatches');
        Route::get('bookings', PassengerBookings::class)->name('bookings');
        Route::get('booking/{booking}', PassengerViewBooking::class)->name('view_booking');
        Route::get('announcement/{announcement}', PassengerAnnouncement::class)->name('announcement');
    });

Route::middleware(['auth', "role_id:" . Role::CONDUCTOR])
    ->name('conductor.')
    ->prefix('conductor')
    ->group(function () {
        Route::get('dashboard', ConductorDashboard::class)->name('dashboard');
        Route::get('dispatches', ConductorDispatches::class)->name('dispatches');
        Route::get('bookings', ConductorBookings::class)->name('bookings');
        Route::get('scanqr', ConductorScanQR::class)->name('scanqr');
        Route::get('booking/{booking:transaction_id}', ConductorViewBooking::class)->name('view_booking');
        Route::get('announcement/{announcement}', ConductorAnnouncement::class)->name('announcement');
    });
