<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Patient books an appointment
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:time_slots,id',
        ]);

        $slot = TimeSlot::with('availability.doctor')->findOrFail($request->slot_id);

        // Check if availability and doctor exist
        if (!$slot->availability || !$slot->availability->doctor) {
            return back()->with('error', 'Selected slot is invalid.');
        }

        $doctor = $slot->availability->doctor;

        if ($slot->is_booked) {
            return back()->with('error', 'This time slot is already booked.');
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $doctor->id,
            'slot_id' => $slot->id,
            'appointment_date' => $slot->availability->available_date,
            'start_time' => $slot->slot_time,
            'end_time' => Carbon::parse($slot->slot_time)
                ->addMinutes((int)($slot->availability->slot_duration ?? 30))
                ->format('H:i:s'),
            'status' => 'pending',
        ]);

        // Mark slot as booked
        $slot->update(['is_booked' => true]);

        // Notify patient
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'message' => "Your appointment with Dr. {$doctor->name} is pending approval.",
            'type' => 'appointment_booked',
            'related_id' => $appointment->id,
        ]);

        // Optional: Notify doctor
        \App\Models\Notification::create([
            'user_id' => $doctor->id,
            'message' => "New appointment request from {$appointment->patient->name} on {$appointment->appointment_date} at {$appointment->start_time}.",
            'type' => 'appointment_request',
            'related_id' => $appointment->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Appointment booked successfully.');
    }

    // API version
    public function storeApi(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:time_slots,id',
        ]);

        $slot = TimeSlot::with('availability.doctor')->findOrFail($request->slot_id);

        if (!$slot->availability || !$slot->availability->doctor) {
            return response()->json(['error' => 'Selected slot is invalid.'], 400);
        }

        $doctor = $slot->availability->doctor;

        if ($slot->is_booked) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $doctor->id,
            'slot_id' => $slot->id,
            'appointment_date' => $slot->availability->available_date,
            'start_time' => $slot->slot_time,
            'end_time' => Carbon::parse($slot->slot_time)
                ->addMinutes((int)($slot->availability->slot_duration ?? 30))
                ->format('H:i:s'),
            'status' => 'pending',
        ]);

        $slot->update(['is_booked' => true]);

        // Notify patient
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'message' => "Your appointment with Dr. {$doctor->name} is pending approval.",
            'type' => 'appointment_booked',
            'related_id' => $appointment->id,
        ]);

        // Optional: Notify doctor
        \App\Models\Notification::create([
            'user_id' => $doctor->id,
            'message' => "New appointment request from {$appointment->patient->name} on {$appointment->appointment_date} at {$appointment->start_time}.",
            'type' => 'appointment_request',
            'related_id' => $appointment->id,
        ]);

        return response()->json(['success' => 'Appointment booked successfully.', 'appointment' => $appointment]);
    }

    // Reschedule appointment
    public function rescheduleApi(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'slot_id' => 'required|exists:time_slots,id',
        ]);

        $appointment = Appointment::where('patient_id', Auth::id())->findOrFail($request->appointment_id);
        $newSlot = TimeSlot::with('availability.doctor')->findOrFail($request->slot_id);

        if (!$newSlot->availability || !$newSlot->availability->doctor) {
            return response()->json(['error' => 'Selected slot is invalid.'], 400);
        }

        $doctor = $newSlot->availability->doctor;

        if ($newSlot->is_booked) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }

        // Release old slot
        if ($appointment->slot_id) {
            TimeSlot::where('id', $appointment->slot_id)->update(['is_booked' => false]);
        }

        $appointment->update([
            'slot_id' => $newSlot->id,
            'appointment_date' => $newSlot->availability->available_date,
            'start_time' => $newSlot->slot_time,
            'end_time' => Carbon::parse($newSlot->slot_time)
                ->addMinutes((int)($newSlot->availability->slot_duration ?? 30))
                ->format('H:i:s'),
            'status' => 'pending',
        ]);

        $newSlot->update(['is_booked' => true]);

        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'message' => "Your appointment with Dr. {$doctor->name} was rescheduled and is pending approval.",
            'type' => 'appointment_rescheduled',
            'related_id' => $appointment->id,
        ]);

        return response()->json(['success' => 'Appointment rescheduled successfully.', 'appointment' => $appointment]);
    }
        public function availableDates($doctorId)
    {
        $dates = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->whereDate('available_date', '>=', now()->toDateString())
            ->pluck('available_date')
            ->unique()
            ->values();

        return response()->json($dates);
    }

    public function availableSlots(Request $request, $doctorId)
    {
        $request->validate(['date' => 'required|date']);

        $availability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->whereDate('available_date', $request->date)
            ->first();

        if (!$availability) {
            return response()->json([]);
        }

        $slots = \App\Models\TimeSlot::where('doctor_id', $doctorId)
            ->where('availability_id', $availability->id)
            ->where('is_booked', false)
            ->orderBy('slot_time')
            ->get(['id', 'slot_time']);

        return response()->json($slots);
    }
}