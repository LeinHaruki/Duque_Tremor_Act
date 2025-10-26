<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorSpecializationController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class);
Route::resource('appointments', AppointmentController::class);
Route::resource('payments', PaymentController::class);
Route::patch('payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid'])->name('payments.mark-paid');
Route::resource('doctor-specializations', DoctorSpecializationController::class);

// Dashboard specific routes
Route::get('/upcoming-appointments', [DashboardController::class, 'upcomingAppointments'])->name('dashboard.upcoming-appointments');
Route::get('/unpaid-payments', [DashboardController::class, 'unpaidPayments'])->name('dashboard.unpaid-payments');

// Timezone setting route
Route::post('/set-timezone', [DashboardController::class, 'setTimezone'])->name('set-timezone');

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/', function () {
    return view('welcome');
});
