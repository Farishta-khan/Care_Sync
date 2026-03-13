<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorSlotController;
use App\Http\Controllers\AppointmentController;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. GET /api/doctors
Route::get('/doctors', function () {
    return response()->json(User::where('role', 'doctor')->with('doctorProfile')->get());
});

// 2. GET /api/doctor/{id}
Route::get('/doctor/{id}', function ($id) {
    return response()->json(User::where('role', 'doctor')->with(['doctorProfile', 'availabilities'])->findOrFail($id));
});

// 3. GET /api/available-slots (Alias for existing logic)
Route::get('/available-slots', [DoctorSlotController::class, 'index']);
Route::get('/doctors/{id}/slots', [DoctorSlotController::class, 'index']); // Legacy for UI

// 4. POST /api/book-appointment
Route::post('/book-appointment', [AppointmentController::class, 'storeApi'])->middleware('auth:sanctum');

// 5. POST /api/reschedule
Route::post('/reschedule', [AppointmentController::class, 'rescheduleApi'])->middleware('auth:sanctum');

Route::get('/doctor/{doctor}/available-dates', [AppointmentController::class, 'availableDates']);
Route::get('/doctor/{doctor}/available-slots', [AppointmentController::class, 'availableSlots']);
