<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-slots', function() {
    $doctor = App\Models\User::where('role', 'doctor')->findOrFail(3);
    $date = \Carbon\Carbon::parse('2026-03-10');
    $dayOfWeek = $date->englishDayOfWeek;

    $availabilities = $doctor->availabilities()->where('available_date', '2026-03-10')->get();
    
    $slots = [];
    $slotDuration = 30;

    $debug = [
        'day' => $dayOfWeek,
        'availability_count' => $availabilities->count()
    ];

    foreach ($availabilities as $availability) {
        $start = \Carbon\Carbon::parse($availability->start_time);
        $end = \Carbon\Carbon::parse($availability->end_time);

        $debug['start'] = $start->toDateTimeString();
        $debug['end'] = $end->toDateTimeString();

        while ($start->copy()->addMinutes($slotDuration)->lte($end)) {
            $slotStart = $start->format('H:i:s');
            $slotEnd = $start->copy()->addMinutes($slotDuration)->format('H:i:s');
            
            $slots[] = [
                'start' => $slotStart,
                'end'   => $slotEnd,
            ];

            $start->addMinutes($slotDuration);
        }
    }
    
    $debug['generated_slots'] = count($slots);
    $debug['slots'] = $slots;
    return $debug;
});

use App\Http\Controllers\DoctorAvailabilityController;
use App\Models\User;

Route::get('/dashboard', function () {
    $user = request()->user();
    
    if ($user->role === 'doctor') {
        $appointments = $user->doctorAppointments()->with('patient')->orderBy('appointment_date')->orderBy('start_time')->get();
        return view('doctor.dashboard', compact('appointments'));
    } 

    if ($user->role === 'patient') {
        $appointments = $user->patientAppointments()->with('doctor')->orderBy('appointment_date')->orderBy('start_time')->get();
        return view('patient.dashboard', compact('appointments'));
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/doctors', function () {
    // Only show doctors who have upcoming availability
    $doctors = User::where('role', 'doctor')
        ->whereHas('doctorAvailabilities', function ($query) {
            $query->whereDate('available_date', '>=', now()->toDateString());
        })
        ->with(['doctorProfile', 'doctorAvailabilities' => function ($query) {
            $query->whereDate('available_date', '>=', now()->toDateString());
        }])
        ->get();
    return view('doctors.index', compact('doctors'));
})->name('doctors.index');

Route::get('/doctors/{id}/book', function ($id) {
    $doctor = User::where('role', 'doctor')->findOrFail($id);
    return view('doctors.book', compact('doctor'));
})->middleware('auth')->name('doctors.book');

Route::post('/appointments/book', [\App\Http\Controllers\AppointmentController::class, 'store'])
    ->middleware('auth')
    ->name('appointments.book');

Route::post('/api/appointments/{id}/approve', function ($id) {
    if (auth()->user()->role !== 'doctor') return response()->json(['message' => 'Unauthorized'], 403);
    
    $appointment = App\Models\Appointment::with('doctor')->where('doctor_id', auth()->id())->findOrFail($id);
    $appointment->update(['status' => 'approved']);
    
    App\Models\Notification::create([
        'user_id' => $appointment->patient_id,
        'message' => "Your appointment with Dr. {$appointment->doctor->name} was approved.",
        'type' => 'appointment_approved',
        'related_id' => $appointment->id,
    ]);

    \Illuminate\Support\Facades\Mail::to($appointment->patient->email)->queue(new \App\Mail\AppointmentStatusUpdated($appointment));
    \Illuminate\Support\Facades\Log::info("Mock SMS sent to User {$appointment->patient_id}: Your appointment has been approved.");
    
    return response()->json(['message' => 'Appointment approved.']);
})->middleware('auth')->name('api.appointments.approve');

Route::post('/api/appointments/{id}/reject', function ($id) {
    if (auth()->user()->role !== 'doctor') return response()->json(['message' => 'Unauthorized'], 403);
    
    $appointment = App\Models\Appointment::with('doctor')->where('doctor_id', auth()->id())->findOrFail($id);
    $appointment->update(['status' => 'rejected']);
    
    App\Models\Notification::create([
        'user_id' => $appointment->patient_id,
        'message' => "Your appointment with Dr. {$appointment->doctor->name} was rejected.",
        'type' => 'appointment_rejected',
        'related_id' => $appointment->id,
    ]);

    \Illuminate\Support\Facades\Mail::to($appointment->patient->email)->queue(new \App\Mail\AppointmentStatusUpdated($appointment));
    \Illuminate\Support\Facades\Log::info("Mock SMS sent to User {$appointment->patient_id}: Your appointment has been rejected.");
    
    return response()->json(['message' => 'Appointment rejected.']);
})->middleware('auth')->name('api.appointments.reject');

Route::middleware('auth')->group(function () {

    Route::get('/doctor/availabilities', [DoctorAvailabilityController::class, 'index'])->name('doctor.availabilities.index');
    Route::post('/doctor/availabilities', [DoctorAvailabilityController::class, 'store'])->name('doctor.availabilities.store');
    Route::delete('/doctor/availabilities/{id}', [DoctorAvailabilityController::class, 'destroy'])->name('doctor.availabilities.destroy');
    Route::post('/book-appointment', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.book');
    Route::get('/doctor/availabilities/{id}/slots', [DoctorAvailabilityController::class, 'getSlots'])->name('doctor.availabilities.slots');

});


Route::get('/api/doctor/{id}/available-dates', [DoctorAvailabilityController::class, 'getAvailableDates']);
Route::get('/api/doctor/{id}/available-slots', [DoctorAvailabilityController::class, 'getAvailableSlots']);
Route::post('/api/book-appointment', [\App\Http\Controllers\AppointmentController::class, 'storeApi'])->middleware('auth');
Route::post('/api/reschedule', [\App\Http\Controllers\AppointmentController::class, 'rescheduleApi'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
